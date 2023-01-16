<?php

namespace JoanRamirez\HandlingDb;

use Illuminate\Support\Facades\DB;

class HandlingDB
{
    private $table;

    private $data;

    private function setName($name): string
    {
        if (!$name)
            $name =  $this->table;

        return now()->format('y_m_d') . '_' . rand() . '_' .  $name;
    }

    /**
     * 
     * @param string $table table from where you will export the data to CSV
     * @param array $where conditional that must be met by the records to be exported
     * @param array $select select only the column you want to get
     * @return $this
     * 
     */
    public function table(string $table, array $where = [], array $select = ['*'])
    {
        $this->table = $table;

        $this->data = DB::table($table)->where($where)->select($select);

        return $this;
    }

    /**
     * 
     * @param string $table main table name to initial the joins
     * @param array $joins table joins via unique identifier
     * @param array $where conditional that must be met by joined records to export
     * @param array $select select only the column you want to get
     * @return $this
     * 
     */
    public function joins(string $table, array $joins = [], array $where = [], array $select = ['*'])
    {
        $this->table($table, $where, $select);

        foreach ($joins as $owner => $foreign)
            $this->data->join(explode('.', $foreign)[0], $owner, '=', $foreign);

        return $this;
    }

    /** 
     * 
     * @param string|null $fileName the name with which you want to save the csv, 
     * if the name is null it will be saved with the name of the table
     * @param string $mode
     * @param string $separator
     * @return string
     * 
     */
    public function export(string $fileName = null, $mode = 'a', string $separator = '|'): string
    {
        $fileName = $this->setName($fileName);

        $folder = database_path("handling-db");

        if (!file_exists($folder))
            mkdir($folder, 0777, true);

        $stream = fopen("$folder\\$fileName.csv", $mode);

        $offset = 0;

        $limit = 50; // export records limited to 50 for better performance 

        $count = $this->data->count();

        $i = 0;
        while ($i < $count) {
            $list = $this->data->offset($offset++ * $limit)
                ->limit($limit)->get();

            foreach ($list as $fields)
                fputcsv($stream, json_decode(json_encode($fields), true), $separator);

            $i += $limit;
        }

        fclose($stream);

        return 'export success!';
    }

    /**
     * 
     * @param string $fileName name of the CSV file you want to import, without the extension
     * @param string $table name of the table where you want to save the CSV
     * @param array $columns the columns of the table where each column of the CSV will be saved,
     * must be written in the same order of the CSV
     * @param string $mode
     * @param string $separator
     * @return string
     * 
     */
    public function import(string $fileName, string $table, $columns = [],  $mode = 'r', $separator = '|')
    {
        $stream = fopen(database_path("handling-db\\$fileName.csv"), $mode);

        $length = 0;

        $array = array();

        while (($value = fgetcsv($stream, $length, $separator)) !== false) {

            for ($i = 0; $i < count($columns); $i++) {
                if (count($value) == count($columns)) {
                    $array[$columns[$i]] = $value[$i];
                }
            }

            DB::table($table)->insert($array);
        }

        return "import success!";
    }
}
