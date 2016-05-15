<?php
use Carbon\Carbon;
use Mockery as m;
use Seanstewart\UpdateScripts\UpdateRepository;
class UpdateRepositoryTest extends PHPUnit_Framework_TestCase {

    public function tearDown()
    {
        m::close();
    }

    /**
     * @test
     */
    public function it_tests_log()
    {
        $repository = $this->getMock(Seanstewart\UpdateScripts\UpdateRepository::class, ['table'], [
            $resolver = m::mock('Illuminate\Database\ConnectionResolverInterface'),
            $table = 'table'
        ]);

        $record = [
            'migration'  => 'file',
            'batch'      => 'batch',
            'details'    => 'details',
            'created_at' => Carbon::now()
        ];

        $tableMock = m::mock('stdClass');
        $tableMock->shouldReceive('insert')->with($record);
        $repository->expects($this->once())->method('table')->willReturn($tableMock);

        $repository->log('file', 'batch', 'details');
    }

}