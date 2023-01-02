<?php

namespace App\Http\HandlingDB;

use Illuminate\Support\Facades\DB;

class HandlingDB
{
    public function export(string $path, string $table, array $where = [], array $select = ['*'])
    {
        $data = DB::table($table)->where($where);

        $file = fopen(public_path('contacts.csv'), 'a');

        $offset = 0;
        $limit = 50;

        for ($i = 0; $i < $data->count();) {

            $data = $data->select($select)
                ->offset($offset++ * $limit)
                ->limit($limit)
                ->get();

            foreach ($data as $line)
                fputcsv($file, json_decode(json_encode($line), true));

            $i += $limit;
        }

        if (fclose($file))
            return "exported!";
    }

    public function joinsExport()
    {
    }

    static function import($path, string $table, $columns = [],  $mode = 'r', $delimiter = ',')
    {
        // $mode: r = read, w = write, a = append
        $csv = fopen(public_path('contacts.csv'), $mode);

        $length = 0;

        $array = array();

        while (($value = fgetcsv($csv, $length, $delimiter)) !== false) {

            for ($i = 0; $i < count($columns); $i++) {
                if (count($value) == count($columns)) {
                    $array[$columns[$i]] = $value[$i];
                }
            }

            DB::table($table)->insert($array);
        }
    }
}
