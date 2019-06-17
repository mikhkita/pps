
<!-- <form action="/adv/setAdvImg?id=" method="POST" id="uploader"> -->
    <div class="upload">
        <h3><?=$title?></h3>
        <!-- <div class="max-files">Оставшееся количество изображений: <span class="max-files-count" data-count=""></span></div> -->
        <div id="uploaderPj">Ваш браузер не поддерживает Flash.</div>
        <div class="b-save-buttons">
            <a href="#" class="plupload_button plupload_save">Сохранить</a>
            <a href="#" class="plupload_button plupload_cancel" >Отменить</a>
        </div>
    </div>
<!-- </form> -->
<script>
$(function () {
    var maxfiles = <?=( (isset($maxFiles) && intval($maxFiles) > 0 && ($maxFiles) < 10000)?$maxFiles:"1")?>,
        error = false,uniq_name = true, multi_select = false;
        if('<?=$selector?>' == '.photo') {
            uniq_name = false;
            multi_select = true;
        }
        <?if(isset($maxFiles) && $maxFiles > 1):?>
        multi_select = true;
        <?endif;?>
    $("#uploaderPj").pluploadQueue({
        runtimes : 'html5',                          
        url : "<? echo Yii::app()->createUrl('/uploader/upload'); ?>",
        max_file_size : '50mb',
        max_file_count: maxfiles,
        chunk_size : '1mb',
        unique_names : uniq_name,
        multi_selection:multi_select,
        resize: {
            width: 2560,
            height: 2560
        },
        filters : [
            {title : "Files", extensions : "<?=$extensions?>" }
        ],
        init : {
            FilesAdded: function(up, files) {
                for( var i = up.files.length-1 ; i > 0 ; i-- ){
                    if( i >= maxfiles ) up.files.splice(i,1);
                }
                if (up.files.length >= maxfiles) {
                    $('.plupload_add').hide();
                    $('#uploaderPj').addClass("blocked_brow");
                }
                $(".max-files-count").html( maxfiles - up.files.length );
            },
            FilesRemoved: function(up, files) {
                $(".max-files-count").html( maxfiles - up.files.length );
                if (up.files.length < maxfiles) {
                    $('.plupload_add').show();
                    $('#uploaderPj').removeClass("blocked_brow");
                }
            },
            UploadComplete: function(){
                var tmpArr = [];
                if( !error ){
                    $(".plupload_filelist .plupload_done").each(function(){
                        tmpArr.push($(this).find("input").eq(0).val());
                    });
                    <?if(isset($tmpPath)):?>
                        $.each( tmpArr, function( index, item ) {
                            tmpArr[index] = "<?=$tmpPath?>/"+item;
                        });
                    <?endif;?>
                    <?if(isset($afterLoad)):?>
                        customHandlers["<?=$afterLoad?>"](tmpArr);
                    <?else:?>
                        $("<?=$selector?>").val(tmpArr.join(',')).trigger("change");
                    <?endif;?>
                    $(".plupload_save").click();
                    if( $(".plupload_start").attr("data-save") == "1" ){
                        $(".plupload_start").parents("form").submit();
                    }
                    // $(".b-save-buttons").fadeIn(300);
                }
            },
            FileUploaded: function(upldr, file, object) {
                var myData;
                try {
                    myData = eval(object.response);
                } catch(err) {
                    myData = eval('(' + object.response + ')');
                }
                if( myData.result != "success" ){
                    error = true;
                    alert(myData.error.message);
                }
            }
        }
    });
    if( !maxfiles ){
        $('.plupload_add').addClass("plupload_disabled");
        $('#uploaderPj').addClass("blocked_brow");
    }

});
</script>
