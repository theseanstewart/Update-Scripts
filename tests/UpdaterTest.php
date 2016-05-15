<?php

use Mockery as m;

class UpdaterTest extends PHPUnit_Framework_TestCase {

    public function tearDown()
    {
        m::close();
    }

    /**
     * @test
     */
    public function it_tests_run()
    {
        $migrator = $this->getMock('\Seanstewart\UpdateScripts\Updater', ['getRepository', 'getPath', 'requireFiles', 'runUpdateList', 'getMigrationFiles'], [
            m::mock('Illuminate\Database\Migrations\MigrationRepositoryInterface'),
            $resolver = m::mock('Illuminate\Database\ConnectionResolverInterface'),
            m::mock('Illuminate\Filesystem\Filesystem'),
        ]);

        $allFiles = ['file_1', 'file_2', 'file_3'];
        $ranFiles = ['file_1'];

        $migrator->expects($this->once())->method('getPath')->willReturn('path');
        $migrator->expects($this->once())->method('getMigrationFiles')->with('path')->willReturn($allFiles);

        $repoMock = $this->getMock('stdClass', ['getRan']);
        $repoMock->expects($this->once())->method('getRan')->willReturn($ranFiles);
        $migrator->expects($this->once())->method('getRepository')->willReturn($repoMock);

        $diff = array_diff($allFiles, $ranFiles);

        $migrator->expects($this->once())->method('requireFiles')->with('path', $diff);
        $migrator->expects($this->once())->method('runUpdateList')->with($diff, []);

        $migrator->run(__DIR__);
    }

    /**
     * @test
     */
    public function it_tests_run_update_list_no_updates()
    {
        $migrator = $this->getMock('\Seanstewart\UpdateScripts\Updater', ['note'], [
            m::mock('Illuminate\Database\Migrations\MigrationRepositoryInterface'),
            $resolver = m::mock('Illuminate\Database\ConnectionResolverInterface'),
            m::mock('Illuminate\Filesystem\Filesystem'),
        ]);

        $updates = [];

        $migrator->expects($this->once())->method('note')->with('<info>No updates to run.</info>');

        $migrator->runUpdateList($updates, []);

    }

    /**
     * @test
     */
    public function it_tests_run_update_list()
    {
        $migrator = $this->getMock('\Seanstewart\UpdateScripts\Updater', ['getRepository', 'runUpdate'], [
            m::mock('Illuminate\Database\Migrations\MigrationRepositoryInterface'),
            $resolver = m::mock('Illuminate\Database\ConnectionResolverInterface'),
            m::mock('Illuminate\Filesystem\Filesystem'),
        ]);

        $updates = ['file_1', 'file_2'];

        $repoMock = $this->getMock('stdClass', ['getNextBatchNumber']);
        $repoMock->expects($this->once())->method('getNextBatchNumber')->willReturn(5);
        $migrator->expects($this->once())->method('getRepository')->willReturn($repoMock);

        $migrator->expects($this->at(1))->method('runUpdate')->with('file_1', 5);
        $migrator->expects($this->at(2))->method('runUpdate')->with('file_2', 5);

        $migrator->runUpdateList($updates, []);
    }

    /**
     * @test
     */
    public function it_tests_run_update()
    {
        $migrator = $this->getMock('\Seanstewart\UpdateScripts\Updater', ['resolve', 'getRepository', 'note'], [
            m::mock('Illuminate\Database\Migrations\MigrationRepositoryInterface'),
            $resolver = m::mock('Illuminate\Database\ConnectionResolverInterface'),
            m::mock('Illuminate\Filesystem\Filesystem'),
        ]);

        $barMock = m::mock('stdClass');
        $barMock->details = 'details';
        $barMock->shouldReceive('run')->once();
        $barMock->shouldReceive('after')->once();

        $repoMock = $this->getMock('stdClass', ['log']);
        $repoMock->expects($this->once())->method('log')->with('file', 'batch', 'details');
        $migrator->expects($this->once())->method('getRepository')->willReturn($repoMock);

        $migrator->expects($this->any())->method('resolve')->with('file')->willReturn($barMock);

        $migrator->runUpdate('file', 'batch');
    }


}