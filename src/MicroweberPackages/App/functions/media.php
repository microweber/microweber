<?php
api_expose_admin('get_media_by_id');
api_expose('upload');
api_expose('upload_progress_check');

api_expose_admin('media/upload', function ($data) {
    return mw()->media_manager->upload($data);
});


api_expose_admin('reorder_media');
api_expose('delete_media');
api_expose_admin('save_media');
api_expose_admin('save_picture');
api_expose('pixum_img');
api_expose('thumbnail_img');

api_expose_admin('get_media');
api_expose_admin('create_media_dir');

api_bind('media/delete_media_file', function ($data) {
    return mw()->media_manager->delete_media_file($data);
});
