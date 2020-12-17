<?php

namespace DiamondDove\SimpleExport\Tests;

use Box\Spout\Reader\CSV\Reader;
use DiamondDove\SimpleExport\ExcelReader;

class ExcelReaderTest extends TestCase
{
    /** @test */
    public function it_can_work_with_an_empty_file()
    {
        $actualCount = ExcelReader::create($this->getStubPath('empty.csv'))
            ->getRows()
            ->count();

        $this->assertEquals(0, $actualCount);
    }

    /** @test */
    public function it_can_work_with_an_file_that_has_headers()
    {
        $rows = ExcelReader::create($this->getStubPath('header-and-rows.csv'))
            ->getRows()
            ->toArray();

        $this->assertEquals([
            [
                'email' => 'john@example.com',
                'first_name' => 'john',
                'last_name' => 'doe',
            ],
            [
                'email' => 'mary-jane@example.com',
                'first_name' => 'mary jane',
                'last_name' => 'doe',
            ],
        ], $rows);
    }

    /** @test */
    public function it_can_work_with_a_file_that_has_only_headers()
    {
        $actualCount = ExcelReader::create($this->getStubPath('only-header.csv'))
            ->getRows()
            ->count();

        $this->assertEquals(0, $actualCount);
    }

    /** @test */
    public function it_can_work_with_a_file_where_the_header_is_too_short()
    {
        $rows = ExcelReader::create($this->getStubPath('header-too-short.csv'))
            ->getRows()
            ->toArray();

        $this->assertEquals([
            [
                'email' => 'john@example.com',
                'first_name' => 'john',
            ],
        ], $rows);
    }

    /** @test */
    public function it_can_work_with_a_file_where_the_row_is_too_short()
    {
        $rows = ExcelReader::create($this->getStubPath('row-too-short.csv'))
            ->getRows()
            ->toArray();

        $this->assertEquals([
            [
                'email' => 'john@example.com',
                'first_name' => '',
            ],
        ], $rows);
    }

    /** @test */
    public function it_can_ignore_the_headers()
    {
        $rows = ExcelReader::create($this->getStubPath('header-and-rows.csv'))
            ->noHeaderRow()
            ->getRows()
            ->toArray();

        $this->assertEquals([
            [
                0 => 'email',
                1 => 'first_name',
                2 => 'last_name',
            ],
            [
                0 => 'john@example.com',
                1 => 'john',
                2 => 'doe',
            ],
            [
                0 => 'mary-jane@example.com',
                1 => 'mary jane',
                2 => 'doe',
            ],
        ], $rows);
    }

    /** @test */
    public function it_can_use_an_alternative_delimiter()
    {
        $rows = ExcelReader::create($this->getStubPath('alternative-delimiter.csv'))
            ->useDelimiter(';')
            ->getRows()
            ->toArray();

        $this->assertEquals([
            [
                'email' => 'john@example.com',
                'first_name' => 'john',
            ],
        ], $rows);
    }

    /** @test */
    public function the_reader_can_get_the_path()
    {
        $path = $this->getStubPath('alternative-delimiter.csv');

        $reader = ExcelReader::create($this->getStubPath('alternative-delimiter.csv'));

        $this->assertEquals($path, $reader->getPath());
    }

    /** @test */
    public function it_combines_headers_with_correct_values_even_though_they_are_returned_in_the_wrong_order()
    {
        $rows = ExcelReader::create($this->getStubPath('columns-returned-in-wrong-order.xlsx'))
            ->getRows()
            ->toArray();

        $this->assertEquals([
            [
                'id' => 11223344,
                'place' => '',
                'status' => 'yes',
            ],
            [
                'id' => 11112222,
                'place' => '',
                'status' => 'no',
            ],
        ], $rows);
    }

    /** @test */
    public function it_can_use_an_offset()
    {
        $rows = ExcelReader::create($this->getStubPath('header-and-rows.csv'))
            ->skip(1)
            ->getRows()
            ->toArray();

        $this->assertEquals([
            [
                'email' => 'mary-jane@example.com',
                'first_name' => 'mary jane',
                'last_name' => 'doe',
            ],
        ], $rows);
    }

    /** @test */
    public function it_can_take_a_limit()
    {
        $rows = ExcelReader::create($this->getStubPath('header-and-rows.csv'))
            ->take(1)
            ->getRows()
            ->toArray();

        $this->assertEquals([
            [
                'email' => 'john@example.com',
                'first_name' => 'john',
                'last_name' => 'doe',
            ],
        ], $rows);
    }

    /** @test */
    public function it_can_call_getRows_twice()
    {
        $reader = ExcelReader::create($this->getStubPath('header-and-rows.csv'));
        $firstRow = $reader->getRows()->first();
        $firstRowAgain = $reader->getRows()->first();

        $this->assertNotNull($firstRow);
        $this->assertNotNull($firstRowAgain);
    }

    /** @test */
    public function it_can_call_first_on_the_collection_twice()
    {
        $reader = ExcelReader::create($this->getStubPath('header-and-rows.csv'));
        $rowCollection = $reader->getRows();
        $firstRow = $rowCollection->first();
        $firstRowAgain = $rowCollection->first();

        $this->assertNotNull($firstRow);
        $this->assertNotNull($firstRowAgain);
    }

    /** @test */
    public function it_allows_setting_the_reader_type_manually()
    {
        $reader = ExcelReader::create('php://input', 'csv');

        $this->assertInstanceOf(Reader::class, $reader->getReader());
    }
}
