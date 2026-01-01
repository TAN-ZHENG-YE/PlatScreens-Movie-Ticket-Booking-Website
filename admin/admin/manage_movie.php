<?php 
require '../../global.php';
$conn = getDatabaseConnection();

if(isset($_GET['id'])){
    $mov = $conn->query("SELECT * FROM movies where id =".$_GET['id']);
    foreach($mov->fetch_array() as $k => $v){
        $meta[$k] = $v;
        if ($k == 'duration') {
            // Split duration into hours and minutes
            $duration_parts = explode(' ', $v);
            $duration_hour = str_replace('h', '', $duration_parts[0]);
            $duration_min = str_replace('m', '', $duration_parts[1]);

            // Store in meta array
            $meta['duration_hour'] = $duration_hour;
            $meta['duration_min'] = $duration_min;
        }
    }
}
?>

<div class="container-fluid">
    <div class="col-lg-12">
        <form id="manage-movie">
            <div class="form-group">
                <input type="hidden" name="id" value="<?php echo isset($_GET['id']) ? $_GET['id'] : '' ?>">
                <label for="" class="control-label">Movie Title</label>
                <input type="text" name="title" required="" class="form-control" value="<?php echo isset($meta['title']) ? $meta['title'] : '' ?>">
            </div>
            <div class="form-group">
                <label for="" class="control-label">Description</label>
                <textarea name="description" class="form-control" id="" cols="30" rows="3" required><?php echo isset($meta['description']) ? $meta['description'] : '' ?></textarea>
            </div>
            <div class="form-group row">
                <label for="" class="control-label col-md-12">Duration (Hours:Minutes)</label>
                <input type="number" name="duration_hour" required class="form-control col-sm-2 offset-md-1" value="<?php echo isset($meta['duration_hour']) ? $meta['duration_hour'] : ''; ?>" max="24" min="0" placeholder="Hour">:
                <input type="number" name="duration_min" required class="form-control col-sm-2" value="<?php echo isset($meta['duration_min']) ? $meta['duration_min'] : ''; ?>" max="59" min="0" placeholder="Min">
            </div>

            <div class="form-group">
                <label for="" class="control-label">Begin Showing Date</label>
                <input name="date_showing" id="" type="date" class="form-control" value="<?php echo isset($meta['date_showing']) ? $meta['date_showing'] : '' ?>" required>
            </div>
            <div class="form-group">
                <label for="" class="control-label">End Showing Date</label>
                <input name="end_date" id="" type="date" class="form-control" value="<?php echo isset($meta['end_date']) ? $meta['end_date'] : '' ?>" required>
            </div>
            <div class="form-group">
                <label for="" class="control-label">Director</label>
                <input type="text" name="director" class="form-control" value="<?php echo isset($meta['director']) ? $meta['director'] : '' ?>">
            </div>
            <div class="form-group">
                <label for="" class="control-label">Genre</label>
                <input type="text" name="genre" class="form-control" value="<?php echo isset($meta['genre']) ? $meta['genre'] : '' ?>">
            </div>
            <div class="form-group">
                <label for="" class="control-label">Language</label>
                <input type="text" name="language" class="form-control" value="<?php echo isset($meta['language']) ? $meta['language'] : '' ?>">
            </div>
            <div class="form-group">
                <label for="" class="control-label">Classification</label>
                <input type="text" name="classification" class="form-control" value="<?php echo isset($meta['classification']) ? $meta['classification'] : '' ?>">
            </div>
            <div class="form-group">
                <label for="" class="control-label">Release Date</label>
                <input name="release_date" id="" type="date" class="form-control" value="<?php echo isset($meta['release_date']) ? $meta['release_date'] : '' ?>" required>
            </div>
            <div class="form-group">
                <label for="" class="control-label">Trailer YouTube Link</label>
                <input type="text" name="trailer_yt_link" class="form-control" value="<?php echo isset($meta['trailer_yt_link']) ? $meta['trailer_yt_link'] : '' ?>">
            </div>
            <div class="form-group">
                <label for="" class="control-label">Cast</label>
                <textarea name="cast" class="form-control" id="" cols="30" rows="3"><?php echo isset($meta['cast']) ? $meta['cast'] : '' ?></textarea>
            </div>
            <div class="form-group">
                <img src="<?php echo $s3_url; ?>/images/<?php echo isset($meta['cover_img']) ? $meta['cover_img'] : '' ?>" alt="" id="cover_img" width="50" height="75">
            </div>
            <div class="form-group input-group">
                <label for="" class="control-label">Movie Poster</label>
                <br>
                <div class="input-group-prepend">
                    <span class="input-group-text">Upload</span>
                </div>
                <div class="custom-file">
                    <input type="file" name="cover" class="custom-file-input" id="cover-img" onchange="displayImg(this,$(this))">
                    <label class="custom-file-label" for="cover-img">Choose file</label>
                </div>
            </div>
            <div class="form-group">
                <img src="<?php echo $s3_url; ?>/images/<?php echo isset($meta['movie_background_image']) ? $meta['movie_background_image'] : '' ?>" alt="" id="background_img" width="50" height="75">
            </div>
            <div class="form-group input-group">
                <label for="" class="control-label">Background Image</label>
                <br>
                <div class="input-group-prepend">
                    <span class="input-group-text">Upload</span>
                </div>
                <div class="custom-file">
                    <input type="file" name="background" class="custom-file-input" id="background-img" onchange="displayBgImg(this,$(this))">
                    <label class="custom-file-label" for="background-img">Choose file</label>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    $('#manage-movie').submit(function(e){
        e.preventDefault();
        start_load();
        $.ajax({
            url: 'ajax.php?action=save_movie',
            data: new FormData($(this)[0]),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            type: 'POST',
            error: err => {
                console.log(err);
            },
            success: function(resp){
                if(resp == 1){
                    alert_toast('Data successfully saved.','success');
                    setTimeout(function(){
                        location.reload();
                    }, 1500);
                }
            }
        });
    });

    function displayImg(input, _this) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#cover_img').attr('src', e.target.result);
                _this.siblings('label').html(input.files[0]['name']);
                _this.siblings('input[name="cover"]').val(input.files[0]['name']);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function displayBgImg(input, _this) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#background_img').attr('src', e.target.result);
                _this.siblings('label').html(input.files[0]['name']);
                _this.siblings('input[name="background"]').val(input.files[0]['name']);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

