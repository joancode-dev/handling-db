<?php

namespace App\Http\HandlingDB;

use Illuminate\Support\Facades\DB;

class HandlingDB
{
    // $mode: r = read, w = write, a = append

    private function exportData($stream, $data, $select, $separator)
    {
        $offset = 0;

        $limit = 50;

        $count = $data->count();

        $i = 0;
        while ($i < $count) {
            $list = $data->select($select)
                ->offset($offset++ * $limit)
                ->limit($limit)
                ->get();

            foreach ($list as $fields)
                fputcsv($stream, json_decode(json_encode($fields), true), $separator);

            $i += $limit;
        }

        if (fclose($stream))
            return "exported!";
    }

    public function export(string $filename, string $table, array $where = [], array $select = ['*'], $mode = 'a', string $separator = '|')
    {
        $data = DB::table($table)->where($where);

        $stream = fopen(public_path('contacts.csv'), $mode);

        $this->exportData($stream, $data, $select, $separator);
    }

    public function exportJoins(string $filename, string $table, array $joins = [], array $where = [], array $select = ['*'], $mode = 'a', $separator = '|')
    {
        $data = DB::table($table)->where($where);

        foreach ($joins as $owner => $foreign)
            $data->join(explode('.', $foreign)[0], $owner, '=', $foreign);

        $stream = fopen(public_path('contacts.csv'), $mode);

        $this->exportData($stream, $data, $select, $separator);
    }

    static function import(string $filename, string $table, $columns = [],  $mode = 'r', $separator = '|')
    {
        $stream = fopen(public_path('contacts.csv'), $mode);

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
    }
}
