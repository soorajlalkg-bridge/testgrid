<style type="text/css">
  .table-bordered {
    border: 1px solid #ddd;
    width: 100%;
  }
  .table-bordered>thead>tr>th {
    border-bottom-width: 2px;
    font-weight: bold;
  }
  .table-striped>tbody>tr:nth-of-type(odd) {
      background-color: #f9f9f9;
  }
  .table-bordered>tbody>tr>td {
    border: 1px solid #ddd;
    padding: 8px;
    line-height: 1.42857143;
    vertical-align: top;
  }
</style>
<h1>Logs List</h1>
<table class="table table-bordered">
  <thead>
    <tr>
      <th>Name</th>
      <th>Email</th>
      <th>Action</th>
      <th>Date</th>
      <th>Time</th>
    </tr>
  </thead>
  <tbody>
    @foreach($logs as $log)
      <tr>
        <td>{{ $log->firstname.' '.$log->lastname }}</td>
        <td>{{ $log->email }}</td>
        <td>{{ $log->action_type }}</td>
        <td>{{ date('Y-m-d', strtotime($log->timestamp)) }}</td>
        <td>{{ date('h:i:s A', strtotime($log->timestamp)) }}</td>
      </tr>
    @endforeach
  </tbody>
</table>