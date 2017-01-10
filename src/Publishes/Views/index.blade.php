<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>


<table class="table table-bordered" id="progressTable">
  <thead>
    <tr>
      <th>#</th>
      <th>Progress</th>
    </tr>
  </thead>
  <tbody>
  {{-- replace dummy array with real data --}}
  @foreach([1,2,3] as $vID)
    <tr>
      <th scope="row">{{$vID}}</th>
      <td>
        <div class="progress">
          <div id="progress_{{$vID}}" class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%;">
            0%
          </div>
        </div>
      </td>
    </tr>
  @endforeach
  </tbody>
</table>

<script src="/assets/lffmpeg.js"></script>
<script type="text/javascript">

  $(document).ready(function() {

    //POPULATE THIS ARRAY WITH VIDEO IDs YOU WANT TO TRACK
    var videos = $("#progressTable > tbody > tr").find('th:first').map(function(){
      return $(this).text()
    }).get();

    LffmpegTrackProgress(videos);

  });
</script>