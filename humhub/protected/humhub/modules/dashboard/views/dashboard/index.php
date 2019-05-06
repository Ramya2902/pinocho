<?php
/**
 * @var \humhub\modules\user\models\User $contentContainer
 * @var bool $showProfilePostForm
 */
use humhub\modules\activity\widgets\ActivityStreamViewer;
use humhub\modules\dashboard\widgets\DashboardContent;
use humhub\modules\dashboard\widgets\Sidebar;
use humhub\widgets\FooterMenu;

?>

<style>
    /* table {
        border-collapse: collapse;
    }
    tr {
        border: solid thin;
    } */
    th, td {
        padding: 5px;
    }
    .table {
        margin: auto;
        width: 50% !important; 
    }
    td.request-id > a, #view-request-access-link > a {
        cursor: pointer;
    }
</style>

<script>
    function returnToDashboard() {
        $('.user-view-request').hide();
        $('.user-new-request').hide();
        $('.user-view-data').hide();
        $('.user-dashboard').show();
    }

    function showNewRequest() {
        $('.user-view-request').hide();
        $('.user-dashboard').hide();
        $('.user-view-data').hide();
        $('.user-new-request').show();
    }
</script>

<div class="container">
    <!-- <h1>End User</h1> -->
    <div class="row text-right">
        <button type="button" class="btn btn-primary" onclick="showNewRequest()">Create New Request</button>
    </div>
    <br>
    <div class="row user-dashboard">
        <div class="col-md-4 user-pending">
            <h3 class="text-center"><b>Pending Requests</b></h3>
            <div class="row">
                <table id="pending-table" class="table text-center">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>ID</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- <tr>
                            <td class="request-date">4/30/2019</td>
                            <td class="request-id"><a>12345</a></td>
                            <td class="request-status">Pending</td>
                        </tr> -->
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-md-4 user-approved">
            <h3 class="text-center"><b>Approved Requests</b></h3>
            <div class="row">
                <table id="approved-table" class="table text-center">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>ID</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- <tr>
                            <td class="request-date">4/29/2019</td>
                            <td class="request-id"><a>1234</a></td>
                            <td class="request-status">Approved</td>
                        </tr>
                        <tr>
                            <td class="request-date">4/20/2019</td>
                            <td class="request-id"><a>12</a></td>
                            <td class="request-status">Expired</td>
                        </tr> -->
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-md-4 user-denied">
            <h3 class="text-center"><b>Denied Requests</b></h3>
            <div class="row">
                <table id="denied-table" class="table text-center">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>ID</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- <tr>
                            <td class="request-date">4/28/2019</td>
                            <td class="request-id"><a>123</a></td>
                            <td class="request-status">Denied</td>
                        </tr> -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="row user-view-request" style="display:none">
    <button type="button" class="btn btn-primary" onclick="returnToDashboard()">Back to Dashboard</button>
        <div class="row">
            <h3 id="view-request-header" class="text-center"><b>View Request - &lt;ID&gt;</b></h3>
            <table id="view-request-table" class="table text-center">
                <thead>
                    <tr>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Title -->
                    <tr>
                        <td>Title</td>
                        <td id="view-request-title">Request Title</td>
                    </tr>
                    <!-- Date -->
                    <tr>
                        <td>Date</td>
                        <td id="view-request-date">4/30/2019</td>
                    </tr>
                    <!-- ID -->
                    <tr>
                        <td>ID</td>
                        <td id="view-request-id">123</td>
                    </tr>
                    <!-- Status -->
                    <tr>
                        <td>Status</td>
                        <td id="view-request-status">Pending</td>
                    </tr>
                    <!-- Description -->
                    <tr>
                        <td>Description</td>
                        <td id="view-request-desc">This is the description</td>
                    </tr>
                    <!-- Dataset Name -->
                    <tr>
                        <td>Requested Dataset</td>
                        <td id="view-request-dataset-name">This is the dataset name</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
 
    <div class="row user-view-data" style="display:none">
    <button type="button" class="btn btn-primary" onclick="returnToDashboard()">Back to Dashboard</button>
        <div class="row">
            <h3 id="view-data-header" class="text-center"><b>View Data - Request &lt;ID&gt;</b></h3>
            <table id="view-request-table" class="table text-center">
                <thead>
                    <tr>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

    <div class="row user-new-request" style="display:none">
        <h3>New Request:</h3>
        <form id="new-request-form" action="/pinocho/newRequest.php">
            <div class="form-group">
                <label for="title">Request Title:</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="form-group">
                <label for="desc">Request Description:</label>
                <input type="text" class="form-control" id="desc" name="desc" required placeholder="Require IDC10, OPS 8-52 data; need data to query faster; secure and visualize analysis; Data to be accessed compliant with DLC3">
	        </div>
            <div class="form-group">
                <label for="dataset">Which dataset are you trying to access?:</label>
                <select id="dataset" name="dataset">
                <!--
                    (' Care site place of service counts ',)
                    (' Counts of condition record ',)
                    (' Counts of condition types ',)
                    (' Counts of drug types ',)
                    (' Counts of persons with any number of exposures to a certain drug ',)
                    (' Distribution of age across all observation period records ',)
                    (' How long does a condition last ',)
                    (' Number of patients by gender, stratified by year of birth ',)
                    (' Number of people continuously observed throughout a year ',)
                    (' Number of people who have at least one observation period that is longer than 365 days ',)
                    (' Patient count per care site place of service ',)
                    (' person, first observation date ',)
                    (' unique patient id taking drug ',)
                    -->
                    <option selected value="1001">Care site place of service counts</option>
                    <option value="1002">Counts of condition record</option>
                    <option value="1003">Counts of condition types</option>
                    <option value="1004">Counts of drug types</option>
                    <option value="1005">Counts of persons with any number of exposures to a certain drug</option>
                    <option value="1006">Distribution of age across all observation period records</option>
                    <option value="1007">How long does a condition last</option>
                    <option value="1008">Number of patients by gender, stratified by year of birth</option>
                    <option value="1009">Number of people continuously observed throughout a year</option>
                    <option value="1010">Number of people who have at least one observation period that is longer than 365 days</option>
                    <option value="1011">Patient count per care site place of service</option>
                    <option value="1012">person, first observation date</option>
                    <option value="1013">unique patient id taking drug</option>
                </select>
            </div>
            <div class="form-group">
                <label for="usage">How will you use the data?:</label>
                <input type="text" class="form-control" id="usage" name="usage" required placeholder="Data processed within community cloud">
	        </div>
            <div class="form-group">
                <label for="store">How will you store the data?:</label>
                <input type="text" class="form-control" id="store" name="store" required placeholder="Data to be stored within community cloud">
            </div>
            <div class="form-group">
                <label for="access-length">How long does the data need to be accessible?:</label>
		        <select id="access-length" name="access-length">
                    <option selected value="1">1 Week</option>
                    <option value="2">2 Week</option>
                    <option value="3">3 Week</option>
                    <option value="4">4 Week</option>
                    <option value="8">2 Months</option>
                    <option value="12">3 Months</option>
                    <option value="16">4 Months</option>
                    <option value="20">5 Months</option>
                    <option value="24">6 Months</option>
                    <option value="52">1 Year</option>
                </select>
            </div>
            <div class="form-group">
                <label for="access-soon">How soon does the data need to be accessible?</label>
                <select id="access-soon" name="access-soon">	
                    <option selected value="1">1 week</option>
                    <option value="2">1-2 weeks</option>
                    <option value="3">More than 2 weeks</option>
                </select>
	        </div>
            <div class="form-group">
                <label for="data-type">What type of data would you like to receive?</label>
                <select id="data-type" name="data-type">	
                    <option selected value="identified">Identified</option>
                    <option value="deidentified">Deidentified</option>
                    <option value="aggregated">Aggregated</option>
                    <option value="limited">Limited</option>
                </select>
	        </div>
            <br>
            <div class="form-group">
                <div class="row">
                    <div class="col-md-3"></div>
                    <div class="col-md-3 text-center">
                        <button id="new-request-submit-btn" type="submit" class="btn btn-primary">Submit</button>
                    </div>
                    <div class="col-md-3 text-center">
                        <button id="new-request-cancel-btn" type="cancel" class="btn btn-danger">Cancel</button>
                    </div>
                    <div class="col-md-3"></div>
                </div>
	        </div>
        </form>
    </div>
        

    <!-- OLD HUMHUB STUFF -->
    <div class="row" style="display:none">
        <div class="col-md-8 layout-content-container">
            <?= DashboardContent::widget([
                'contentContainer' => $contentContainer,
                'showProfilePostForm' => $showProfilePostForm
            ]);
            ?>
        </div>
        <div class="col-md-4 layout-sidebar-container">
            <?= Sidebar::widget([
                'widgets' => [
                    [
                        ActivityStreamViewer::class,
                        ['streamAction' => '/dashboard/dashboard/activity-stream'],
                        ['sortOrder' => 150]
                    ]
                ]
            ]);
            ?>
            <?= FooterMenu::widget(['location' => FooterMenu::LOCATION_SIDEBAR]); ?>
        </div>
    </div>
    <!-- OLD HUMHUB STUFF -->
</div>

<script>
    $( document ).ready(function() {
        $('#topbar-second > .container > .nav > .dropdown').hide();
        $('#topbar-second > .container > .nav > li.directory').attr('style','display:none !important');
        console.dir($('#topbar-second > .container > .nav').children());
        $('#topbar-second > .container > .nav').children().eq(1).children().eq(0).html('<i class="fa fa-tachometer"></i><br>View Requests');
        $('#topbar-second > .container > .nav').children().eq(2).children().eq(0).html('<i class="fa fa-tachometer"></i><br>New Request');

        $('#topbar-second').hide();

        var user_id = "<?php echo $user_id ?>";
        var user_type = "<?php echo $user_type ?>";

        console.log(user_type);

        $.ajax({
            url: "/pinocho/viewRequests.php",
            type: 'GET',
            dataType: 'json',
            data: {
                'user_id': user_id
            },
            success: function(data) {
                console.dir(data);
                if(data.pending == undefined ||data.pending.length == 0) {
                    $('#pending-table > tbody').append('<tr><td colspan="3">There are no pending requests!</td></tr>');
                }else {
                    $.each(data.pending, function (i) {
                        console.dir(data.pending[i]);
                        var dateCreated = new Date(data.pending[i].dateCreated);
                        var dateString = (dateCreated.getMonth() + 1) + '/' + dateCreated.getDate() + '/' +  dateCreated.getFullYear();
                        var status = data.pending[i].status.charAt(0).toUpperCase() + data.pending[i].status.slice(1);

                        $('#pending-table > tbody').append('<tr><td class="request-date">' + dateString + '</td><td class="request-id"><a>' + data.pending[i].id + '</a></td><td class="request-status">' + status + '</td></tr>');
                    });
                }

                if(data.approved == undefined || data.approved.length == 0) {
                    $('#approved-table > tbody').append('<tr><td colspan="3">There are no approved requests!</td></tr>');
                }else {
                    $.each(data.approved, function (i) {
                        console.dir(data.approved[i]);
                        var dateCreated = new Date(data.approved[i].dateCreated);
                        var dateString = (dateCreated.getMonth() + 1) + '/' + dateCreated.getDate() + '/' +  dateCreated.getFullYear();
                        var status = data.approved[i].status.charAt(0).toUpperCase() + data.approved[i].status.slice(1);

                        $('#approved-table > tbody').append('<tr><td class="request-date">' + dateString + '</td><td class="request-id"><a>' + data.approved[i].id + '</a></td><td class="request-status">' + status + '</td></tr>');
                    });
                }

                if(data.denied == undefined || data.denied.length == 0) {
                    $('#denied-table > tbody').append('<tr><td colspan="3">There are no denied requests!</td></tr>');
                }else {
                    $.each(data.denied, function (i) {
                        console.dir(data.denied[i]);
                        var dateCreated = new Date(data.denied[i].dateCreated);
                        var dateString = (dateCreated.getMonth() + 1) + '/' + dateCreated.getDate() + '/' +  dateCreated.getFullYear();
                        var status = data.denied[i].status.charAt(0).toUpperCase() + data.denied[i].status.slice(1);

                        $('#denied-table > tbody').append('<tr><td class="request-date">' + dateString + '</td><td class="request-id"><a>' + data.denied[i].id + '</a></td><td class="request-status">' + status + '</td></tr>');
                    });
                }


                $('table > tbody > tr > td.request-id > a').each(function() {
                    $(this).click(function() {
                        console.dir("request clicked: " + $(this).text());
                        // console.log($(this).parent().parent().children('.request-date').text());
                        // console.log($(this).parent().parent().children('.request-status').text());
                        $.ajax({
                            url: "/pinocho/viewRequest.php",
                            type: 'GET',
                            dataType: 'json',
                            data: {
                                "request_id": $(this).text()
                            },
                            success: function(data) {
                                console.dir(data);

                                var dateCreated = new Date(data.dateCreated);
                                var dateString = (dateCreated.getMonth() + 1) + '/' + dateCreated.getDate() + '/' +  dateCreated.getFullYear();
                                var status = data.status.charAt(0).toUpperCase() + data.status.slice(1);

                                if(data.status == "approved") {
                                    if(data.dateExpired != null) {
                                        var dateExpired = new Date(data.dateExpired);
                                        var today = new Date();

                                        if(dateExpired < today) {
                                            status = "Expired";
                                        }

                                        expiredDateString = (dateExpired.getMonth() + 1) + '/' + dateExpired.getDate() + '/' +  dateExpired.getFullYear();
                                        if($('#view-request-expired-date').length) {
                                            $('#view-request-expired-date').text(expiredDateString);
                                        }else {
                                            $('#view-request-status').parent().after('<tr><td>Expiration Date</td><td id="view-request-expired-date">' + expiredDateString + '</td></tr>');
                                        }
                                    }

                                    if($('#view-request-access-link').length) {
                                        $('#view-request-access-link').html('<a>Click Here</a>');
                                    }else {
                                        $('#view-request-dataset-name').parent().after('<tr><td>Access Data</td><td id="view-request-access-link"><a href="#">Click Here</a></td></tr>');
                                    }

                                    $('#view-request-access-link > a').click(function() {
                                        console.dir("view data link clicked");

                                        $('div.user-view-data > div > table > thead > tr').html('<td colspan="2">The request returned an empty dataset.</td>');
                                        $('div.user-view-data > div > table > tbody').html("");

                                        $.ajax({
                                            url: "/pinocho/viewData.php",
                                            type: 'GET',
                                            dataType: 'json',
                                            data: {
                                                "request_id": $('#view-request-id').text()
                                            },
                                            success: function(data) {
                                                console.dir(data);

                                                if(data.data.length != 0) {
                                                    $('div.user-view-data > div > table > thead > tr').html("");

                                                    // determine all column names
                                                    var keys = [];
                                                    for(var key in data.data[0]) {
                                                        if(data.data[0].hasOwnProperty(key) && /^\d+$/.test(key) == false) {
                                                            keys.push(key);
                                                        }
                                                    }

                                                    // console.dir(keys);

                                                    for(var key in keys) {
                                                        $('div.user-view-data > div > table > thead > tr').append("<td>" + keys[key] + "</td>");
                                                    }

                                                    for(var row in data.data) {
                                                        $('div.user-view-data > div > table > tbody').append("<tr></tr>");
                                                        // console.dir(data.data[row]);
                                                        for(var key in keys) {
                                                            $('div.user-view-data > div > table > tbody > tr').last().append("<td>" + data.data[row][keys[key]] + "</td>");
                                                        }
                                                    }
                                                }

                                                $('#view-data-header > b').text("View Data - Request " + $('#view-request-id').text());

                                                $('div.user-dashboard').hide();
                                                $('div.user-view-request').hide();
                                                $('div.user-view-data').show();
                                            },
                                            error: function(res) {
                                                console.log("error - GET => /pinocho/viewData.php");
                                                console.dir(res);
                                            }
                                        });
                                    });
                                }else if(data.status == "denied") {
                                    if(data.deniedDescription != null) {
                                        if($('#view-request-denied-desc').length) {
                                            $('#view-request-denied-desc').text(data.deniedDescription);
                                        }else {
                                            $('#view-request-status').parent().after('<tr><td>Denied Description</td><td id="view-request-denied-desc">' + data.deniedDescription + '</td></tr>');
                                        }
                                    }

                                    if($('#view-request-access-link').length) {
                                        $('#view-request-access-link').parent().remove();
                                    }
                                }

                                $('#view-request-header > b').text("View Request - " + data.id);
                                $('#view-request-title').text(data.title);
                                $('#view-request-date').text(dateString);
                                $('#view-request-id').text(data.id);
                                $('#view-request-status').text(status);
                                $('#view-request-desc').text(data.description);
                                $('#view-request-dataset-name').text(data.dataset_name);

                                $('div.user-dashboard').hide();
                                $('div.user-view-request').show();
                            },
                            error: function(res) {
                                console.log("error - GET => /pinocho/viewRequest.php");
                                console.dir(res);
                            }
                        });
                    });
                });
            },
            error: function(data) {
                console.dir(data);
            }
        });

        $('#new-request-form').submit(function(event) {
            event.preventDefault();

            // console.dir($(this));

            // console.dir($('#title'));
            if($('#title').val() == null || $('#title').val() == "") {
                alert("Title is invalid cannot be empty.");
                return;
            }

            // console.dir($('#desc'));
            if($('#desc').val() == null || $('#desc').val() == "") {
                alert("Description cannot be empty.");
                return;
            }

            // console.dir($('#usage'));
            if($('#usage').val() == null || $('#usage').val() == "") {
                alert("Usage description cannot be empty.");
                return;
            }

            // console.dir($('#store'));
            if($('#store').val() == null || $('#store').val() == "") {
                alert("Storage description cannot be empty.");
                return;
            }

            // console.dir($('#access-length'));
            if($('#access-length').val() == null) {
                alert("You must select an access length.");
                return;
            }

            // console.dir($('#access-soon'));
            if($('#access-soon').val() == null) {
                alert("You must select an access deadline.");
                return;
            }

            // console.dir($('#data-type'));
            if($('#data-type').val() == null) {
                alert("You must select a data type.");
                return;
            }

            // console.dir($('#data-type'));
            if($('#dataset').val() == null) {
                alert("You must select a dataset.");
                return;
            }

            $.ajax({
                url: "/pinocho/newRequest.php",
                type: 'POST',
                dataType: 'json',
                data: {
                    "title": $('#title').val(),
                    "description": $('#desc').val(),
                    "usage": $('#usage').val(),
                    "store": $('#store').val(),
                    "access_length": $('#access-length').val(),
                    "access_soon": $('#access-soon').val(),
                    "data_type": $('#data-type').val(),
                    "dataset_id": $('#dataset').val(),
                    "user_id": user_id,
                    "user_type": user_type
                },
                success: function(data) {
                    console.dir(data);

                    if(data.status == "OK") {
                        alert("Successfully added new request. Total calculation time = " + data.calculateTime + "ms.\nPress OK to return to dashboard");
                        location.reload();
                    }else {
                        alert("Failed to add new request. Error:\n" + data.error + "\nPlease try again.");
                    }
                },
                error: function(data) {
                    console.dir(data);
                }
            });
        });

        $('#new-request-cancel-btn').click(function() {
            location.reload();
        });
    });
</script>
