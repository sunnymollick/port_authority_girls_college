<div class="row">
    <div class="col-md-12 col-sm-12 table-responsive">
        <table id="view_details" class="table table-bordered table-hover">
            <tbody>
            <tr>
                <td> Slug</td>
                <td> :</td>
                <td> {{ $page->slug }} </td>
            </tr>
            <tr>
                <td> Title</td>
                <td> :</td>
                <td> {{ $page->title }} </td>
            </tr>
            <tr>
                <td> Description</td>
                <td> :</td>
                <td> {!! $page->description !!} </td>
            </tr>
            <tr>
                <td> Summery</td>
                <td> :</td>
                <td> {{ $page->summery }} </td>
            </tr>
            <tr>
                <td> Status</td>
                <td> :</td>
                <td> @php $status = $page->status ? '<span class="label label-success">Active</span>' : '<span class="label label-danger">Inactive</span>' ;  @endphp {!! $status !!}   </td>
            </tr>
            <tr>
                <td> Featured Image</td>
                <td> :</td>
                <td><img src="{{ asset($page->file_path) }}" class="img-responsive img-thumbnail" width="200px"></td>
            </tr>
            </tbody>
        </table>
    </div>
</div>