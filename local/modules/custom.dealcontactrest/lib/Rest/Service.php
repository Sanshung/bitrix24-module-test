<?php

namespace Custom\DealContactRest\Rest;

use Bitrix\Crm\DealTable;
use Bitrix\Crm\ContactTable;

class Service
{
    public static function getDescription(): array
    {
        return [
            'custom.dealcontactrest' => [
                'custom.dealcontactrest.dealcontact.list' => [
                    'callback' => [self::class, 'list'],
                    'options' => [],
                ],
            ],
        ];
    }
    
    public static function list(array $params, int $n, \CRestServer $server): array
    {
        //1) Должна быть возможность фильтрации по любому полю этих сущностей (аналогично фильтру ORM ).
        $filter = $params['filter'] ?? [];
        
       //2) Должна быть возможность сортировки по любому из полей(аналогично сортировке ORM)
        $order  = $params['order'] ?? [];
        
         //3)Должна быть предусмотрена возможность указание числа получаемых элементов и сдвига(limit,offset  ORM)
        $offset = (int)($params['offset'] ?? 0);
        //4) Должен быть предохранитель, от получения больше 100 элементов за раз, чтоб не перегрузить систему случайно.
        $limit  = min((int)($params['limit'] ?? 20), 100);
        
        $query = DealTable::query()
            ->setSelect(['*', 'CONTACT.*'])
            ->registerRuntimeField('CONTACT', [
                'data_type' => ContactTable::class,
                'reference' => ['=this.CONTACT_ID' => 'ref.ID'],
                'join_type' => 'LEFT',
            ])
            ->setLimit($limit)
            ->setOffset($offset);
        
        if ($filter) {
            $query->setFilter($filter);
        }
        if ($order) {
            $query->setOrder($order);
        }
        
        $items = [];
        $res = $query->exec();
        
        while ($row = $res->fetch()) {
            $deal = [];
            $contact = [];
            
            foreach ($row as $k => $v) {
                if(str_starts_with($k, 'CRM_DEAL_CONTACT')) {
                    $contact[$k] = $v;
                }
                else{
                    $deal[$k] = $v;
                }
            }
            
            $items[] = [
                'DEAL' => $deal,
                'CONTACT' => $contact,
            ];
        }
        
        $countQuery = DealTable::query();
        if ($filter) {
            $countQuery->setFilter($filter);
        }
        
        $total = $countQuery->exec()->getSelectedRowsCount();
        
        return [
            'total' => $total,
            'limit' => $limit,
            'offset' => $offset,
            'next_offset' => ($offset + $limit < $total) ? $offset + $limit : null, //6) Должно быть возвращено  какой следующий сдвиг(offset) нужно передать, чтоб получить элементы находящееся далее. Если дальше элементов нету то null.
            'items' => $items,
        ];
    }
}
