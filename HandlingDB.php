<?php

namespace App\Http\HandlingDB;

class HandlingDB
{
    public function export(string $entity, array $where = [], array $select = ['*'])
    {
        $data = $entity::where($where);
        $file = fopen(public_path('contacts.csv'), 'a');

        $offset = 0;
        $limit = 50;

        for ($i = 0; $i < $data->count();) {

            $data = $data->select($select)
                ->offset($offset++ * $limit)
                ->limit($limit)
                ->get();

            foreach ($data as $line)
                fputcsv($file, $line->getAttributes());

            $i += $limit;
        }

        if (fclose($file))
            return "exported!";
    }

    public function joinsExport()
    {
    }

    static function import(string $entity, array $where = [], string $format = null)
    {
    }
}
