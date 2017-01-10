function LffmpegTrackProgress(videos) {

    var interval = 2000;
    var inProgress = false;

    function doAjax() {
        $.ajax({
            url: '/api/lffmpeg/v1/video/progress',
            data: {'videos[]': videos},
            dataType: 'json',
            type: 'GET',
            success: function (data) {

                $.each(data, function (k, v) {

                    if(v.video_status == null || v.video_status == 0) {

                        $("#progress_" + k).removeClass('progress-bar-danger');
                        $("#progress_" + k).addClass('progress-bar-success');
                        $("#progress_" + k).text('Pending...');
                    }
                    else if(v.video_status == 1) {

                        $("#progress_" + k).removeClass('progress-bar-danger');
                        $("#progress_" + k).addClass('progress-bar-success');
                        $("#progress_" + k).text('Generating thumbs...');
                    }
                    else if(v.video_status == 2) {

                        $("#progress_" + k).removeClass('progress-bar-danger');
                        $("#progress_" + k).addClass('progress-bar-success');

                        inProgress = false;

                        $.each(v.encoding_progress, function(i, vv) {

                            if(vv.status == 'ENCODING') {

                                inProgress = true;

                                $("#progress_" + k).text(vv.percent + "%(" + vv.quality + ")");
                                $("#progress_" + k).attr('aria-valuenow', vv.percent);
                                $("#progress_" + k).css('width', vv.percent + "%");

                                $("#progress_" + k).removeClass('active');
                                $("#progress_" + k).removeClass('progress-bar-striped');

                                $("#progress_" + k).addClass('active');
                                $("#progress_" + k).addClass('progress-bar-striped');

                                return false;
                            }

                            if(vv.status == 'FAILED') {
                                $("#progress_" + k).text('Failed at quality ' + vv.quality);
                                $("#progress_" + k).css('width', "100%");
                                $("#progress_" + k).removeClass('active');
                                $("#progress_" + k).removeClass('progress-bar-striped');
                                $("#progress_" + k).removeClass('progress-bar-success');
                                $("#progress_" + k).addClass('progress-bar-danger');

                                return false;
                            }

                            if(!inProgress) {
                                $("#progress_" + k).text('Queued next quality');
                                $("#progress_" + k).css('width', "100%");
                                $("#progress_" + k).removeClass('active');
                                $("#progress_" + k).removeClass('progress-bar-striped');
                            }

                        });
                    }
                    else if(v.video_status == 3){

                        $("#progress_" + k).removeClass('progress-bar-danger');
                        $("#progress_" + k).addClass('progress-bar-success');

                        $("#progress_" + k).text('DONE');
                        $("#progress_" + k).css('width', "100%");
                        $("#progress_" + k).removeClass('active');
                        $("#progress_" + k).removeClass('progress-bar-striped');
                    }
                    else{
                        $("#progress_" + k).text('FAILED');
                        $("#progress_" + k).removeClass('active');
                        $("#progress_" + k).removeClass('progress-bar-striped');
                        $("#progress_" + k).removeClass('progress-bar-striped');
                        $("#progress_" + k).removeClass('progress-bar-success');
                        $("#progress_" + k).addClass('progress-bar-danger');
                    }

                });

                setTimeout(doAjax, interval);
            }
        });
    }

    doAjax();
}