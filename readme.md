# Sportal task
### Instructions:
#### 1. Clone the project locally
#### 2. Run `composer install`
#### 3. Replace `.env.example` with as new file `.env` and include `APP_SECRET` and uncomment `DATABASE_URL` and replace the url with your credentials
#### 4. Run the migrations
#### 5. Run `symfony serve -d`
#### 6. Access `/seed_posts` path from browser client/postman for inserting example db data 

---
## Endpoints

### Get list of Posts

`GET /api/posts`

    [
        {
            "title": "Lorem ipsum",
            "content": "<p>Lorem ipsum dolor sit amet.</p>",
            "created_at": "2023-03-28 21:47:44",
            "publish_at": "2023-03-29 22:47:44",
            "status": true
        },
        {
            "title": "Lorem ipsum",
            "content": "<p>Lorem ipsum dolor sit amet.</p>",
            "created_at": "2023-03-28 21:47:44",
            "publish_at": null,
            "status": false
        },
    ]

### Parameters

    active=1

    Accepted values: boolean (1|0)
    Default value: 1
    Not required, if you don't set active, you will receive only the posts with status active (1).

####

    page=1

    Accepted values: int (1|2|3|...n)
    Default value: 1
    Not required, if you don't set page, you will receive the first page.

####

    day=2023-03-28

    Accepted values: Y-m-d formatted date 
    Default value: null
    Not required, if you don't set day, you will receive every post.
####

    format=csv

    Accepted values: string (json|csv|xml) 
    Default value: null
    Not required, if you don't set format, you will receive the data in json format.

####

### Get single Post

`GET /api/posts/1`

    {
        "title": "Lorem ipsum",
        "content": "<p>Lorem ipsum dolor sit amet.</p>",
        "created_at": "2023-03-28 21:47:44",
        "publish_at": "2023-03-29 22:47:44",
        "status": true
    }

### Create new Post

`POST /api/post`

### Parameters

    title=Lorem Ipsum

    Accepted values: string
    Required

####
    content=<p>Lorem Ipsum Dolor Sit Amet.</p>

    Accepted values: string
    Required

####