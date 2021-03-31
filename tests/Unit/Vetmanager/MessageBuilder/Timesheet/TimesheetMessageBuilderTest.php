<?php


namespace Tests\Unit\Vetmanager\MessageBuilder\Timesheet;


use App\Exceptions\VmEmptyScheduleException;
use App\Http\Helpers\Rest\SchedulesApi;
use App\Vetmanager\MessageBuilder\Admission\TimesheetMessageBuilder;
use Tests\TestCase;

class TimesheetMessageBuilderTest extends TestCase
{
    public function testBuildMessage()
    {
        $timesheets = $this->timesheets();
        $schedulesApi = $this->createMock(SchedulesApi::class);
        $schedulesApi->method('getTypeNameById')
            ->willReturn('some timesheet type');
        $messageBuilder = new TimesheetMessageBuilder($timesheets, $schedulesApi);
        $this->assertEquals(
            "29.03.2021\n11:54:01 - 12:54:01\nsome timesheet type\n\n",
            $messageBuilder->buildMessage()
        );
    }

    public function testBuildMessageWithEmptyTimesheet()
    {
        $timesheets = [];
        $schedulesApi = $this->createMock(SchedulesApi::class);
        $schedulesApi->method('getTypeNameById')
            ->willReturn('some timesheet type');
        $messageBuilder = new TimesheetMessageBuilder($timesheets, $schedulesApi);
        $this->expectException(VmEmptyScheduleException::class);
        $messageBuilder->buildMessage();
    }

    private function timesheets()
    {
        return [
            [
                'begin_datetime' => '2021-03-29 11:54:01',
                'end_datetime' => '2021-03-29 12:54:01',
                'type' => 2
            ]
        ];
    }
}