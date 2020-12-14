# API

## Медиа

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

## Избранное

### Получение списка избранного

Роут: 
```
GET /api/favorites/list
```
Ответ:
```
[
  {
    "id": "Ly90ci5hbmlkdWIuY29tL2FuaW1lX3R2L2FuaW1lX29uZ29pbmcvMTExNDktbWFnaWNoZXNrYXlhLWJpdHZhLWp1anV0c3Uta2Fpc2VuLmh0bWw=",
    "url": "//tr.anidub.com/anime_tv/anime_ongoing/11149-magicheskaya-bitva-jujutsu-kaisen.html",
    "tracker_id": "anidub",
    "title": "Магическая битва",
    "original_title": "Jujutsu Kaisen",
    "poster": "https://static2.statics.life/tracker/poster/5d7a3c5ea3.jpg",
    "series_count": "10 из 24",
    "is_favorite": 1,
    "created_at": "2020-11-22T22:27:45.000000Z",
    "updated_at": "2020-12-08T16:00:05.000000Z",
    "added_to_favorites_at": "2020-11-22T22:27:55.000000Z"
  }
]
```

### Добавление в избранное

Роут: 
```
GET /api/favorites/add
```
Запрос:
```
{
  "id": "Ly90ci5hbmlkdWIuY29tL2FuaW1lX3R2L2FuaW1lX29uZ29pbmcvMTExNDktbWFnaWNoZXNrYXlhLWJpdHZhLWp1anV0c3Uta2Fpc2VuLmh0bWw=" (id медиа)
}
```
Ответ:
```
{
    "status": "success"
}
```

### Удаление из избранного

Роут: 
```
GET /api/favorites/remove
```
Запрос:
```
{
  "id": "Ly90ci5hbmlkdWIuY29tL2FuaW1lX3R2L2FuaW1lX29uZ29pbmcvMTExNDktbWFnaWNoZXNrYXlhLWJpdHZhLWp1anV0c3Uta2Fpc2VuLmh0bWw=" (id медиа)
}
```
Ответ:
```
{
    "status": "success"
}
```

## Загрузки

### Начало загрузки торрента

Роут: 
```
GET /api/download
```
Запрос:
```
{
  "id": 1326 (id торрента)
}
```
Ответ:
```
{
    "status": "success"
}
```

### Получение списка текущих загрузок

Роут: 
```
GET /api/download/list
```
Ответ:
```
[
    {
        "hash": "7543652b927df0a76983a86680a74207531d5216",
        "name": "id:1326",
        "download_speed_in_bytes_per_second": 50022,
        "estimate_in_seconds": 8640000,
        "size_in_bytes": 1866310709,
        "state_original": "pausedDL",
        "progress": "0.00062329600017314",
        "media": {
            "id": "Ly90ci5hbmlkdWIuY29tL2FuaW1lX3R2L2Z1bGwvOTkwMy1zZW0tc21lcnRueWgtZ3JlaG92LXpuYW1lbmllLXN2eWFzY2hlbm5veS12b3lueS10di0yLXRoZS1zZXZlbi1kZWFkbHktc2lucy1zaWducy1vZi1ob2x5LXdhci10di0yLTAxLWl6LTA0Lmh0bWw=",
            "url": "//tr.anidub.com/anime_tv/full/9903-sem-smertnyh-grehov-znamenie-svyaschennoy-voyny-tv-2-the-seven-deadly-sins-signs-of-holy-war-tv-2-01-iz-04.html",
            "tracker_id": "anidub",
            "title": "Семь смертных грехов: Знамение священной войны ТВ-2",
            "original_title": "Nanatsu no Taizai: Seisen no Shirushi TV-2",
            "poster": "https://static2.statics.life/tracker/poster/eb5b3de671.jpg",
            "series_count": "04 из 04",
            "is_favorite": 0,
            "created_at": "2020-06-27T12:36:28.000000Z",
            "updated_at": "2020-11-11T14:33:16.000000Z",
            "added_to_favorites_at": null
        },
        "torrent": {
            "id": 1326,
            "name": "TV (720p)",
            "url": "https://tr.anidub.com/engine/download.php?id=17130",
            "content_type": "anime",
            "voice_acting": "JAM",
            "quality": "TV (720p)",
            "size": "1.74 GB",
            "size_int": null,
            "downloads": 20119,
            "season": [
                1,
                4
            ],
            "created_at": "2020-10-27T12:54:31.000000Z",
            "updated_at": "2020-12-14T10:45:32.000000Z",
            "media_id": "Ly90ci5hbmlkdWIuY29tL2FuaW1lX3R2L2Z1bGwvOTkwMy1zZW0tc21lcnRueWgtZ3JlaG92LXpuYW1lbmllLXN2eWFzY2hlbm5veS12b3lueS10di0yLXRoZS1zZXZlbi1kZWFkbHktc2lucy1zaWducy1vZi1ob2x5LXdhci10di0yLTAxLWl6LTA0Lmh0bWw="
        }
    }
]
```

### Приостановка загрузки

Роут: 
```
GET /api/download/pause
```
Запрос:
```
{
    "hash": "7543652b927df0a76983a86680a74207531d5216"
}
```

### Продолжение загрузки

Роут: 
```
GET /api/download/resume
```
Запрос:
```
{
    "hash": "7543652b927df0a76983a86680a74207531d5216"
}
```

### Отмена (удаление) загрузки

Роут: 
```
GET /api/download/delete
```
Запрос:
```
{
    "hash": "7543652b927df0a76983a86680a74207531d5216"
}
```
