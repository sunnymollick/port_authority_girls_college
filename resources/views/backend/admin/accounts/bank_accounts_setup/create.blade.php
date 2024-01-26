<form id='create' action="" enctype="multipart/form-data" method="post" accept-charset="utf-8">
    <div id="status"></div>
    <div class="form-group col-md-3 col-sm-12">
        <label for=""> Bank Name </label>
        <select id="bank_id" name="bank_id" class="form-control filter">
            <option value="" selected disabled>Select Bank</option>
            @foreach($banks as $bank)
                <option value="{{$bank->id}}">{{$bank->bank_name}}</option>
            @endforeach
        </select>
        <span id="error_class_id" class="has-error"></span>
    </div>
    <div class="form-group col-md-3 col-sm-12">
        <label for=""> Accounts Name </label>
        <input type="text" class="form-control" id="account_name" name="account_name" value=""
               placeholder="" required>
        <span id="error_account_name" class="has-error"></span>
    </div>
    <div class="form-group col-md-3 col-sm-12">
        <label for=""> Accounts Number </label>
        <input type="text" class="form-control" id="account_number" name="account_number" value=""
               placeholder="" required>
        <span id="error_account_number" class="has-error"></span>
    </div>
    <div class="form-group col-md-3 col-sm-12">
        <label for=""> Branch Name </label>
        <input type="text" class="form-control" id="branch_name" name="branch_name" value=""
               placeholder="" required>
        <span id="error_branch_name" class="has-error"></span>
    </div>
    <div class="clearfix"></div>
    <div class="form-group col-md-3 col-sm-12">
        <label for=""> Contact Person </label>
        <input type="text" class="form-control" id="contact_person" name="contact_person" value=""
               placeholder="">
        <span id="error_contact_person" class="has-error"></span>
    </div>
    <div class="form-group col-md-3 col-sm-12">
        <label for=""> Phone </label>
        <input type="text" class="form-control" id="phone" name="phone" value=""
               placeholder="">
        <span id="error_phone" class="has-error"></span>
    </div>
    <div class="form-group col-md-3 col-sm-12">
        <label for=""> Designition </label>
        <input type="text" class="form-control" id="designition" name="designition" value=""
               placeholder="">
        <span id="error_designition" class="has-error"></span>
    </div>

    <div class="form-group col-md-3 col-sm-12">
        <label for=""> Email </label>
        <input type="text" class="form-control" id="email" name="email" value=""
               placeholder="">
        <span id="error_email" class="has-error"></span>
    </div>
    <div class="clearfix"></div>
    <div class="form-group col-md-12">
        <button type="submit" class="btn btn-success button-submit"
                data-loading-text="Loading..."><span class="fa fa-save fa-fw"></span> Save
        </button>
        <button type="button" class="btn btn-default" data-dismiss="modal"><span
                class="fa fa-times-circle fa-fw"></span> Cancel
        </button>
    </div>
    <div class="clearfix"></div>
</form>


<script>
    $(document).ready(function () {

        $('.filter').select2();

        $('#loader').hide();

        $('#create').validate({// <- attach '.validate()' to your form
            // Rules for form validation
            rules: {
                name: {
                    required: true
                }
            },
            // Messages for form validation
            messages: {
                name: {
                    required: 'Enter name'
                }
            },
            submitHandler: function (form) {

                var myData = new FormData($("#create")[0]);
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                myData.append('_token', CSRF_TOKEN);

                $.ajax({
                    url: 'bankAccounts',
                    type: 'POST',
                    data: myData,
                    dataType: 'json',
                    cache: false,
                    processData: false,
                    contentType: false,
                    beforeSend: function () {
                        $('#loader').show();
                        $(".button-submit").prop('disabled', true); // disable button
                    },
                    success: function (data) {
                        if (data.type === 'success') {
                            reload_table();
                            notify_view(data.type, data.message);
                            $('#loader').hide();
                            $(".button-submit").prop('disabled', false); // disable button
                            $("html, body").animate({scrollTop: 0}, "slow");
                            $('#myModal').modal('hide'); // hide bootstrap modal

                        } else if (data.type === 'error') {
                            if (data.errors) {
                                $.each(data.errors, function (key, val) {
                                    $('#error_' + key).html(val);
                                });
                            }
                            $("#status").html(data.message);
                            $('#loader').hide();
                            $(".button-submit").prop('disabled', false); // disable button

                        }
                    }
                });
            }
            // <- end 'submitHandler' callback
        });                    // <- end '.validate()'

    });
</script>