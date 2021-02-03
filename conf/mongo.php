<?php

return [
    'doc' => [
        'hosts' => [
            //此处的索引(0,1)对应host_map中的cid字段值
            0 => [
                'hosts' => 'rs1-2-1.mongo.int.yidian-inc.com:27017,rs1-2-2.mongo.int.yidian-inc.com:27017,rs1-2-3.mongo.int.yidian-inc.com:27017,rs1-2-12.mongo.int.yidian-inc.com:27017,rs1-2-13.mongo.int.yidian-inc.com:27017',
                'replica_set' => 'rs1-2'
            ],
            1 => [
                'hosts' => 'rs1-2-old1.mongo.int.yidian-inc.com:27017,rs1-2-old2.mongo.int.yidian-inc.com:27017,rs1-2-old3.mongo.int.yidian-inc.com:27017,rs1-2-old5.mongo.int.yidian-inc.com:27017,rs1-2-old6.mongo.int.yidian-inc.com:27017',
                'replica_set' => 'rs1-2-old'
            ],
        ],
        'options' => [
            'connectTimeoutMS' => 500,
            'socketTimeoutMS' => 6000,
            'wTimeoutMS' => 3000,
            'replicaSet' => ''
        ],
        'type_map' => [
            'root' => 'array',
            'document' => 'array'
        ],
        'host_map' => [
            //此处的cid字段值对应hosts的索引(0,1)
            'default' => ['cid' => 0, 'db' => 'serving', 'collection' => 'displayDocument'],
            '20190101' => ['cid' => 1, 'db' => 'serving', 'collection' => 'displayDocument'],
            'news' => ['cid' => 1, 'db' => 'serving', 'collection' => 'displayDocument'],
            'K' => ['cid' => 0, 'db' => 'knowledge', 'collection' => 'displayDocument'],
            'F' => ['cid' => 0, 'db' => 'forum', 'collection' => 'displayDocument'],
            'V' => ['cid' => 0, 'db' => 'video', 'collection' => 'displayDocument'],
            'V_20190101' => ['cid' => 1, 'db' => 'video', 'collection' => 'displayDocument'],
            'G' => ['cid' => 0, 'db' => 'gallery', 'collection' => 'displayDocument'],
            'S' => ['cid' => 0, 'db' => 'supplement', 'collection' => 'displayDocument'],
            'T' => ['cid' => 0, 'db' => 'trend', 'collection' => 'displayDocument'],
            'A' => ['cid' => 0, 'db' => 'audio', 'collection' => 'displayDocument'],
            'J' => ['cid' => 0, 'db' => 'joke', 'collection' => 'displayDocument'],
            'O' => ['cid' => 0, 'db' => 'opponent', 'collection' => 'displayDocument'],
            'C' => ['cid' => 0, 'db' => 'comic', 'collection' => 'displayDocument'],
            'L' => ['cid' => 0, 'db' => 'live', 'collection' => 'displayDocument'],
            'N' => ['cid' => 0, 'db' => 'novel', 'collection' => 'displayDocument'],
            'B' => ['cid' => 0, 'db' => 'film', 'collection' => 'displayDocument'],
            'D' => ['cid' => 0, 'db' => 'cinema', 'collection' => 'displayDocument'],
            'E' => ['cid' => 0, 'db' => 'entity', 'collection' => 'displayDocument'],
        ],
    ]
];
