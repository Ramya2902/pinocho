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
    td.request-id > a {
        cursor: pointer;
    }
</style>

<script>
    function returnToDashboard() {
        $('.admin-view-request').hide();
        $('.admin-new-request').hide();
        $('.admin-dashboard').show();
    }
</script>

<div class="container">
    <h1>Admin</h1>
    <div class="row admin-dashboard">
        <div class="col-md-4 admin-pending">
            <h3 class="text-center">Pending Requests</h3>
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
        <div class="col-md-4 admin-approved">
            <h3 class="text-center">Approved Requests</h3>
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
        <div class="col-md-4 admin-denied">
            <h3 class="text-center">Denied Requests</h3>
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

    <div class="row admin-view-request" style="display:none">
    <button type="button" class="btn btn-primary" onclick="returnToDashboard()">Back to Dashboard</button>
        <div class="row">
            <h3 id="view-request-header" class="text-center">View Request - &lt;ID&gt;</h3>
            <table id="view-request-table" class="table text-center">
                <thead>
                    <tr>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
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
                    <!-- User Access? -->
                    <tr>
                        <td>User Access (sp?) *Implement Me*</td>
                        <td id="view-request-user-access">Local/External</td>
                    </tr>
                    <!-- Usage -->
                    <tr>
                        <td>How They Will Use the Data</td>
                        <td id="view-request-usage">This is the data usage description</td>
                    </tr>
                    <!-- Storage -->
                    <tr>
                        <td>How They Will Store the Data</td>
                        <td id="view-request-store">This is the data storage description</td>
                    </tr>
                    <!-- Data Type -->
                    <tr>
                        <td>Data Type</td>
                        <td id="view-request-data-type">Identified</td>
                    </tr>
                    <!-- IRB Compliance -->
                    <tr>
                        <td>IRB Compliant?</td>
                        <td id="view-request-irb-compliance">No</td>
                    </tr>
                    <!-- IRB Compliance -->
                    <tr>
                        <td>HIPAA Compliant?</td>
                        <td id="view-request-hipaa-compliance">Yes</td>
                    </tr>
                    <!-- Accessibility Length -->
                    <tr>
                        <td>How Long They Will Access the Data</td>
                        <td id="view-request-access-length">1 Week</td>
                    </tr>
                    <!-- Accessibility Deadline -->
                    <tr>
                        <td>How Soon They Want Access to the Data</td>
                        <td id="view-request-access-soon">1 Week</td>
                    </tr>
                    <!-- Risk Level -->
                    <tr>
                        <td>Risk Level</td>
                        <td id="view-request-risk">High</td>
                    </tr>
                </tbody>
            </table>
            <br>
            <div id="denied-description-container" class="row">
                <label for="deniedDescription">Reason for Denying Request</label>
                <input type="text" class="form-control" id="deniedDescription" name="deniedDescription">
            </div>
            <br><br>
            <div id="approve-deny-btns-container" class="row">
                <div class="col-md-3"></div>
                <div class="col-md-3 text-center">
                    <button type="button" id="approve-btn" class="btn btn-success">Approve</button>
                </div>
                <div class="col-md-3 text-center">
                    <button type="button" id="deny-btn" class="btn btn-danger">Deny</button>
                </div>
                <div class="col-md-3"></div>
            </div>
            <br>
            <br>
        </div>
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

        console.dir({'user_id': null, 'admin_id': user_id});

        $.ajax({
            url: "/pinocho/viewRequests.php",
            type: 'GET',
            dataType: 'json',
            data: {
                'user_id': null,
                'admin_id': user_id
            },
            success: function(data) {
                console.dir(data);
                if(data.pending == undefined || data.pending.length == 0) {
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
                                        $('#view-request-access-link').html('<a href="#">Click Here</a>');
                                    }else {
                                        $('#view-request-risk').parent().after('<tr><td>Access Data</td><td id="view-request-access-link"><a href="#">Click Here</a></td></tr>');
                                    }

                                    $('#denied-description-container').hide();
                                    $('#approve-deny-btns-container').hide();
                                }else if(data.status == "denied") {
                                    if(data.deniedDescription != null) {
                                        if($('#view-request-denied-desc').length) {
                                            $('#view-request-denied-desc').text(data.deniedDescription);
                                        }else {
                                            $('#view-request-status').parent().after('<tr><td>Denied Description</td><td id="view-request-denied-desc">' + data.deniedDescription + '</td></tr>');
                                        }
                                    }

                                    $('#denied-description-container').hide();
                                    $('#approve-deny-btns-container').hide();

                                    if($('#view-request-access-link').length) {
                                        $('#view-request-access-link').parent().remove();
                                    }

                                    if($('#view-request-expired-date').length) {
                                        $('#view-request-expired-date').parent().remove();
                                    }
                                }else {
                                    if($('#view-request-denied-desc').length) {
                                        $('#view-request-denied-desc').parent().remove();
                                    }
                                    if($('#view-request-expired-date').length) {
                                        $('#view-request-expired-date').parent().remove();
                                    }
                                    if($('#view-request-access-link').length) {
                                        $('#view-request-access-link').parent().remove();
                                    }

                                    $('#denied-description-container').show();
                                    $('#approve-deny-btns-container').show();
                                }

                                var accessLengthString = "";
                                if(data.weeksAccessible == 1) {
                                    accessLengthString = data.weeksAccessible + " Week";
                                }else if(data.weeksAccessible < 4) {
                                    accessLengthString = data.weeksAccessible + " Weeks";
                                }else if(data.weeksAccessible == 4) {
                                    accessLengthString = "1 Month"
                                }else if(data.weeksAccessible < 52) {
                                    accessLengthString = (data.weeksAccessible + 1) / 4 + " Months"
                                }else {
                                    accessLengthString = "1 Year"
                                }

                                var accessSoonString = "";
                                if(data.weeksUntilAccessible == 1) {
                                    accessSoonString = "1 Week";
                                }else if(data.weeksUntilAccessible == 2) {
                                    accessSoonString = "1-2 Weeks";
                                }else {
                                    accessSoonString = "More than 2 weeks";
                                }

                                var risk = "High";
                                if(data.risk != null) {
                                    risk = data.risk.charAt(0).toUpperCase() + data.risk.slice(1);
                                }

                                console.dir(data.compliance);
                                var irb = "No";
                                var hipaa = "No";
                                if(data.compliance != null) {
                                    if(data.compliance.includes("irb")) {
                                        irb = "Yes";
                                    }
                                    if(data.compliance.includes("hipaa")) {
                                        hipaa = "Yes";
                                    }
                                }
                                

                                var dataType = "";
                                if(data.dataType != null) {
                                    dataType = data.dataType.charAt(0).toUpperCase() + data.dataType.slice(1);
                                }

                                $('#view-request-header').text("View Request - " + data.id);
                                $('#view-request-date').text(dateString);
                                $('#view-request-id').text(data.id);
                                $('#view-request-status').text(status);
                                $('#view-request-desc').text(data.description);
                                $('#view-request-usage').text(data.howDataUsed);
                                $('#view-request-store').text(data.howDataStored);
                                $('#view-request-data-type').text(dataType);
                                $('#view-request-irb-compliance').text(irb);
                                $('#view-request-hipaa-compliance').text(hipaa);
                                $('#view-request-access-length').text(accessLengthString);
                                $('#view-request-access-soon').text(accessSoonString);
                                $('#view-request-risk').text(risk);

                                $('div.admin-dashboard').hide();
                                $('div.admin-view-request').show();
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

        $('#approve-btn').click(function(event) {
            console.dir($('#view-request-id').text());

            var request_id = $('#view-request-id').text();

            var expirationDate = new Date($('#view-request-date').text());
            var numberDays = 0;
            var numberDaysString = $('#view-request-access-length').text();
            if(numberDaysString.includes('Weeks')) {
                numberDays = parseInt(numberDaysString.split(' ')[0]) * 7;
            }else if(numberDaysString.includes('Months')) {
                numberDays = parseInt(numberDaysString.split(' ')[0]) * 4 * 7;
            }else if(numberDaysString.includes('Week')) {
                numberDays = 7;
            }else if(numberDaysString.includes('Month')) {
                numberDays = 28;
            }else {
                numberDays = 365;
            }

            expirationDate.setDate(expirationDate.getDate() + numberDays);

            $.ajax({
                url: "/pinocho/approveRequest.php",
                type: 'POST',
                dataType: 'json',
                data: {
                    "request_id": request_id,
                    "admin_id": user_id,
                    "expiration_date": expirationDate
                },
                success: function(data) {
                    console.dir(data);

                    if(data.status == "OK") {
                        alert("Successfully approved request " + request_id + ". Press OK to return to dashboard");
                        location.reload();
                    }else {
                        alert("Failed to approve request " + request_id + ". Error:\n" + data.error + "\nPlease try again.");
                    }
                },
                error: function(data) {
                    console.dir(data);
                }
            });
        });

        $('#deny-btn').click(function(event) {
            if($('#deniedDescription').val() == null || $('#deniedDescription').val() == "") {
                alert("Reason for Denying a Request cannot be empty.");
                return;
            }

            $.ajax({
                url: "/pinocho/denyRequest.php",
                type: 'POST',
                dataType: 'json',
                data: {
                    "request_id": $('#view-request-id').text(),
                    "admin_id": user_id,
                    "deniedDescription": $('#deniedDescription').val()
                },
                success: function(data) {
                    console.dir(data);

                    if(data.status == "OK") {
                        alert("Successfully denied request" + $('#view-request-id').text() + ". Press OK to return to dashboard");
                        location.reload();
                    }else {
                        alert("Failed to deny request" + $('#view-request-id').text() + ". Error:\n" + data.error + "\nPlease try again.");
                    }
                },
                error: function(data) {
                    console.dir(data);
                }
            });
        });
        
    });
</script>
