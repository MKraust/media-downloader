## API

### Список торрент-трекеров

Роут: 
```
GET /api/trackers
```
Ответ:
```
[
    {
        "id":"anidub",
        "title":"AniDub",
        "icon":"http:\/\/media.mkraust.ru\/media\/tracker\/anidub.png"
    },
    {
        "id":"animedia",
        "title":"AniMedia",
        "icon":"http:\/\/media.mkraust.ru\/media\/tracker\/animedia.ico"
    },
    {
        "id":"fast_torrent",
        "title":"Fast Torrent",
        "icon":"http:\/\/media.mkraust.ru\/media\/tracker\/fast-torrent.ico"
    }
]
```

### Поиск по трекеру

Роут: 
```
GET /api/search
```
Запрос:
```
{
  "tracker_id": "anidub",
  "search_query": "строка поиска",
  "offset": 0
}
```
Ответ:
```
[
    {
        "id":"Ly90ci5hbmlkdWIuY29tL2FuaW1lX3R2L2Z1bGwvODgwNS11ZGFyLWtyb3ZpLXN0cmlrZS10aGUtYmxvb2QuaHRtbA==",
        "url":"\/\/tr.anidub.com\/anime_tv\/full\/8805-udar-krovi-strike-the-blood.html",
        "tracker_id":"anidub",
        "title":"\u0423\u0434\u0430\u0440 \u043a\u0440\u043e\u0432\u0438",
        "original_title":"Strike the Blood",
        "poster":"https:\/\/static3.statics.life\/online\/poster\/8311e1506f.jpg",
        "series_count":"24 \u0438\u0437 24",
        "is_favorite":0,
        "created_at":"2020-06-27T11:58:25.000000Z",
        "updated_at":"2020-06-27T11:58:25.000000Z",
        "added_to_favorites_at":null,
        "torrents":[
            {
                "id":1209,
                "name":"TV (720p)",
                "url":"https:\/\/tr.anidub.com\/engine\/download.php?id=3632",
                "content_type":"anime",
                "voice_acting":"JAM, Ancord, Nika Lenina",
                "quality":"TV (720p)",
                "size":"7.68 GB",
                "size_int":null,
                "downloads":26864,
                "season":[
                    1,
                    24
                ],
                "created_at":"2020-10-13T17:12:28.000000Z",
                "updated_at":"2020-10-13T17:12:28.000000Z",
                "media_id":"Ly90ci5hbmlkdWIuY29tL2FuaW1lX3R2L2Z1bGwvODgwNS11ZGFyLWtyb3ZpLXN0cmlrZS10aGUtYmxvb2QuaHRtbA=="
            }
        ]
    }
]
```

### Получение одной сущности медиа

Роут: 
```
GET /api/media
```
Запрос:
```
{
  "id": "Ly90ci5hbmlkdWIuY29tL2FuaW1lX3R2L2Z1bGwvODgwNS11ZGFyLWtyb3ZpLXN0cmlrZS10aGUtYmxvb2QuaHRtbA=="
}
```
Ответ:
```
{
    "id":"Ly90ci5hbmlkdWIuY29tL2FuaW1lX3R2L2Z1bGwvODgwNS11ZGFyLWtyb3ZpLXN0cmlrZS10aGUtYmxvb2QuaHRtbA==",
    "url":"\/\/tr.anidub.com\/anime_tv\/full\/8805-udar-krovi-strike-the-blood.html",
    "tracker_id":"anidub",
    "title":"\u0423\u0434\u0430\u0440 \u043a\u0440\u043e\u0432\u0438",
    "original_title":"Strike the Blood",
    "poster":"https:\/\/static3.statics.life\/online\/poster\/8311e1506f.jpg",
    "series_count":"24 \u0438\u0437 24",
    "is_favorite":0,
    "created_at":"2020-06-27T11:58:25.000000Z",
    "updated_at":"2020-06-27T11:58:25.000000Z",
    "added_to_favorites_at":null,
    "torrents":[
        {
            "id":1209,
            "name":"TV (720p)",
            "url":"https:\/\/tr.anidub.com\/engine\/download.php?id=3632",
            "content_type":"anime",
            "voice_acting":"JAM, Ancord, Nika Lenina",
            "quality":"TV (720p)",
            "size":"7.68 GB",
            "size_int":null,
            "downloads":26864,
            "season":[
                1,
                24
            ],
            "created_at":"2020-10-13T17:12:28.000000Z",
            "updated_at":"2020-10-13T17:12:28.000000Z",
            "media_id":"Ly90ci5hbmlkdWIuY29tL2FuaW1lX3R2L2Z1bGwvODgwNS11ZGFyLWtyb3ZpLXN0cmlrZS10aGUtYmxvb2QuaHRtbA=="
        }
    ]
}
```
