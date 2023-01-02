<?php

namespace JoanRamirez\HandlingDb;

use Illuminate\Support\Facades\DB;

class HandlingDB
{
    private $data;

    public function export(string $filename, $mode = 'a', string $separator = '|')
    {
        $stream = fopen(public_path('contacts.csv'), $mode);

        $offset = 0;

        $limit = 50;

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
    }

    public function table(string $table, array $where = [], array $select = ['*'])
    {
        $this->data = DB::table($table)->where($where)->select($select);

        return $this;
    }

    public function joins(string $table, array $joins = [], array $where = [], array $select = ['*'])
    {
        $this->table($table, $where, $select);

        foreach ($joins as $owner => $foreign)
            $this->data->join(explode('.', $foreign)[0], $owner, '=', $foreign);

        return $this;
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
