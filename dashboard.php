<?php
session_start();
if(!isset($_SESSION['SessionUserId'])) {
    header("Location: login.php"); 
    exit();
}
$isAdmin = $_SESSION['SessionIsAdmin'];
?>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css" />
        <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap-theme.min.css" />
        <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap-theme-override.css" />
        <link rel="stylesheet" type="text/css" href="css/jquery.Jcrop.css" />
        <link rel="stylesheet" type="text/css" href="plugins/font-awesome/css/font-awesome.min.css" />

        <script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
        <script type="text/javascript" src="bootstrap/js/bootstrap.min.js?"></script>
        <script type="text/javascript" src="js/general.js"></script>
        <script type="text/javascript" src="js/jquery.Jcrop.js"></script>

        <style>
            .content-container {
            width: 350px;
            height: 200px;
            border-radius: 10px;
            border: 2px solid black;
        }

        .inner-square {
            width: 100px;
            height: 100px;
            margin-top: 60px;
            margin-left: 235px;
            border:2px solid black;
        }
        .inner-square1 {
            width: 100px;
            height: 100px;
            margin-top: 50px;
            margin-left: 30px;
            border:2px solid black;
        }
        .inner-square-calendar {
            width: 100px;
            height: 100px;
            margin-top: 20px;
            margin-left: 115px;
            border:2px solid black;
        }
        .inner-square-calendar1 {
            width: 100px;
            height: 100px;
            margin-top: 50px;
            margin-left: 30px;
            border:2px solid black;
        }
       </style>
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <h2 style="width: 50%">Welcome <?php echo $_SESSION['SessionUserName']; ?>!</h2>
                    <a href="logout.php" style="margin-left: 50px;">Logout</a>
                </div>
            </div>
            <?php if ($isAdmin) { ?>
                <div class="row">
                    <div class="col-sm-12">
                        <label for="User List"><b>User List</b></label>
                        <select name="userlist" id="userlist" onchange="selectUserList();">
                            <?php
                            $users = array(
                                'admin' => array(
                                    'userId' => 1,
                                    'password' => 'admin',
                                    'isAdmin' => true,
                                    'name' => 'Admin'
                                ),
                                'user1' => array(
                                    'userId' => 2,
                                    'password' => 'user1',
                                    'isAdmin' => false,
                                    'name' => 'User 1'
                                ),
                                'user2' => array(
                                    'userId' => 3,
                                    'password' => 'user2',
                                    'isAdmin' => false,
                                    'name' => 'User 2'
                                ),
                            );
                            ?>
                            <option value=0>select</option>
                            <?php
                            foreach ($users as $userKey => $user) {
                                if ($user['isAdmin']) {
                                    continue;
                                }
                                ?>
                                <option value="<?php echo $user['userId']; ?>"><?php echo $user['name']; ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-8">
                        <div class="row" id="divUserImages">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <button type="button" onclick="printMessage()">Print</button>
                        <br/>
                        <span id="spnMessage"></span>
                    </div>
                </div>
                <script type="text/javascript">
                    function selectUserList() {
                        var userVal = document.getElementById("userlist").value;
                        $.ajax({
                            url : 'ajax/get-user-images.php?userid=' + userVal,
                            type : 'GET',
                            success : function (result) {
                                $('#divUserImages').html(result);
                            },
                            error : function () {
                                alert("error");
                            }
                        })
                    }
                    function printMessage() {
                        var checkBoxes = $('#divUserImages').find('input[name="userimages"]:checked');
                        console.log('length: ' + checkBoxes.length);
                        console.log($('#divUserImages').find('input[name="userimages"]:checked').length);
                        if (checkBoxes.length) {
                            $('#spnMessage').html('Images printed successfully');
                        } else {
                            $('#spnMessage').html('Please select at least one image.');
                        }
                    }
                </script>
            <?php } else { ?>
                <div class="row" style="margin-left: 300px;">
                    <div class="col-xs-10 col-sm-10">
                        <label class="label-sm">Select Type</label>
                    </div>
                    <br/><br/>
                    <div class="col-xs-10 col-sm-10">
                        <input class="pull-left" type="radio" name="selecttype" id="selecttype_Grid"  value="Grid" style="cursor: pointer; margin-top: 8px; margin-right: 5px;"/><label class="label-sm pull-left" for="selecttype_Grid" style="cursor: pointer; margin-right: 15px;">Collage</label>
                        <input class="pull-left" type="radio" name="selecttype" id="selecttype_BusinessCard" value="BusinessCard"  style="cursor: pointer; margin-top: 8px; margin-right: 5px;"/><label class="label-sm pull-left" for="selecttype_BusinessCard" style="cursor: pointer; margin-right: 15px;">Business Card</label>
                        <input class="pull-left" type="radio" name="selecttype" id="selecttype_Calendar" value="Calendar"  style="cursor: pointer; margin-top: 8px; margin-right: 5px;"/><label class="label-sm pull-left" for="selecttype_Calendar" style="cursor: pointer; margin-right: 15px;">Calendar</label>
                    </div>
                    <br/><br/>
                    <div id="ImageOptionCollageDiv" class="col-sm-4" hidden>
                        <select name="ImageOptionCollage" id="ImageOptionCollage" class="form-control label-sm" style="margin-right: 10px;" onchange="selectImageOption();">
                            <option value="0">Select</option>
                            <option value="horizontal">Horizontal</option>
                            <option value="vertical">Vertical</option>
                        </select>
                    </div>
                    <div id="ImageOptionCardDiv" class="col-sm-4" hidden>
                        <select name="ImageOptionCard" id="ImageOptionCard" class="form-control label-sm" style="margin-right: 10px;" onchange="selectImageOptionCard();">
                            <option value="0">Select</option>
                            <option value="template1">Template1</option>
                            <option value="template2">Template2</option>
                        </select>
                    </div>
                    <div id="ImageOptionCalendarDiv" class="col-sm-4" hidden>
                        <select name="ImageOptionCalendar" id="ImageOptionCalendar" class="form-control label-sm" style="margin-right: 10px;" onchange="selectImageOptionCalendar();">
                            <option value="0">Select</option>
                            <option value="template1">Template1</option>
                            <option value="template2">Template2</option>
                        </select>
                    </div>
                    <br/><br/>
                </div> 
                <div class="row" id="divHorizontalGrid" style="height: 300px; width: 300px;margin-left: 300px;" hidden> 
                    <div class="column" style="height: 150px; width: 300px;border:3px solid black;">
                        <span id="media-links-horizontal-1-<?php echo $_SESSION['SessionUserId']; ?>">
                        <a align="center" onclick="UploadPictureShow(1, 'collag', 'horizontal')" href="Javascript:void(0);" title="Image. Max. upload limit (16MB)" style="margin-right:7px; float:left;"><img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHg9IjBweCIgeT0iMHB4IgogICAgIHdpZHRoPSI1MCIgaGVpZ2h0PSI1MCIKICAgICB2aWV3Qm94PSIwIDAgNDggNDgiCiAgICAgc3R5bGU9IjtmaWxsOiMwMDgwMDA7IgogICAgIGNsYXNzPSJpY29uIGljb25zOC1waWN0dXJlIj48ZyBpZD0ic3VyZmFjZTEiPjxwYXRoIHN0eWxlPSIgZmlsbDojRjU3QzAwOyIgZD0iTSA0MCA0MSBMIDggNDEgQyA1LjgwMDc4MSA0MSA0IDM5LjE5OTIxOSA0IDM3IEwgNCAxMSBDIDQgOC44MDA3ODEgNS44MDA3ODEgNyA4IDcgTCA0MCA3IEMgNDIuMTk5MjE5IDcgNDQgOC44MDA3ODEgNDQgMTEgTCA0NCAzNyBDIDQ0IDM5LjE5OTIxOSA0Mi4xOTkyMTkgNDEgNDAgNDEgWiAiPjwvcGF0aD48cGF0aCBzdHlsZT0iIGZpbGw6I0ZGRjlDNDsiIGQ9Ik0gMzggMTYgQyAzOCAxNy42NTYyNSAzNi42NTYyNSAxOSAzNSAxOSBDIDMzLjM0Mzc1IDE5IDMyIDE3LjY1NjI1IDMyIDE2IEMgMzIgMTQuMzQzNzUgMzMuMzQzNzUgMTMgMzUgMTMgQyAzNi42NTYyNSAxMyAzOCAxNC4zNDM3NSAzOCAxNiBaICI+PC9wYXRoPjxwYXRoIHN0eWxlPSIgZmlsbDojOTQyQTA5OyIgZD0iTSAyMCAxNiBMIDkgMzIgTCAzMSAzMiBaICI+PC9wYXRoPjxwYXRoIHN0eWxlPSIgZmlsbDojQkYzNjBDOyIgZD0iTSAzMSAyMiBMIDIzIDMyIEwgMzkgMzIgWiAiPjwvcGF0aD48L2c+PC9zdmc+" alt="Image" style="width:25px; margin-left:50px"></a>
                        </span>
                        <p name="media-input-horizontal-1-<?php echo $_SESSION['SessionUserId']; ?>" id="media-input-horizontal-1-<?php echo $_SESSION['SessionUserId']; ?>" value=""> </p>
                    </div>
                    <div class="column" style="height: 150px; width: 300px;border:3px solid black;"> 
                        <span id="media-links-horizontal-2-<?php echo $_SESSION['SessionUserId']; ?>">
                            <a align="center" onclick="UploadPictureShow(2,'collag', 'horizontal')" href="Javascript:void(0);" title="Image. Max. upload limit (16MB)" style="margin-right:7px; float:left;"><img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHg9IjBweCIgeT0iMHB4IgogICAgIHdpZHRoPSI1MCIgaGVpZ2h0PSI1MCIKICAgICB2aWV3Qm94PSIwIDAgNDggNDgiCiAgICAgc3R5bGU9IjtmaWxsOiMwMDgwMDA7IgogICAgIGNsYXNzPSJpY29uIGljb25zOC1waWN0dXJlIj48ZyBpZD0ic3VyZmFjZTEiPjxwYXRoIHN0eWxlPSIgZmlsbDojRjU3QzAwOyIgZD0iTSA0MCA0MSBMIDggNDEgQyA1LjgwMDc4MSA0MSA0IDM5LjE5OTIxOSA0IDM3IEwgNCAxMSBDIDQgOC44MDA3ODEgNS44MDA3ODEgNyA4IDcgTCA0MCA3IEMgNDIuMTk5MjE5IDcgNDQgOC44MDA3ODEgNDQgMTEgTCA0NCAzNyBDIDQ0IDM5LjE5OTIxOSA0Mi4xOTkyMTkgNDEgNDAgNDEgWiAiPjwvcGF0aD48cGF0aCBzdHlsZT0iIGZpbGw6I0ZGRjlDNDsiIGQ9Ik0gMzggMTYgQyAzOCAxNy42NTYyNSAzNi42NTYyNSAxOSAzNSAxOSBDIDMzLjM0Mzc1IDE5IDMyIDE3LjY1NjI1IDMyIDE2IEMgMzIgMTQuMzQzNzUgMzMuMzQzNzUgMTMgMzUgMTMgQyAzNi42NTYyNSAxMyAzOCAxNC4zNDM3NSAzOCAxNiBaICI+PC9wYXRoPjxwYXRoIHN0eWxlPSIgZmlsbDojOTQyQTA5OyIgZD0iTSAyMCAxNiBMIDkgMzIgTCAzMSAzMiBaICI+PC9wYXRoPjxwYXRoIHN0eWxlPSIgZmlsbDojQkYzNjBDOyIgZD0iTSAzMSAyMiBMIDIzIDMyIEwgMzkgMzIgWiAiPjwvcGF0aD48L2c+PC9zdmc+" alt="Image" style="width:25px; margin-left:50px"></a>
                        </span>
                            <p name="media-input-horizontal-2-<?php echo $_SESSION['SessionUserId']; ?>" id="media-input-horizontal-2-<?php echo $_SESSION['SessionUserId']; ?>" value=""> </p>
                    </div>
                    <br/>
                    <input type="button" value="save" onclick="saveGridAndBusinessCardImage('<?php echo $_SESSION['SessionUserId']; ?>', 'collag', 'horizontal')">
                </div>
                <div class="row" id="divVerticalGrid" style="height: 300px; width: 300px;margin-left: 300px;" hidden> 
                    <div class="column" style="height: 150px; width: 150px;border:3px solid black;float: left;">
                        <span id="media-links-vertical-1-<?php echo $_SESSION['SessionUserId']; ?>">
                        <a align="center" onclick="UploadPictureShow(1, 'collag', 'vertical')" href="Javascript:void(0);" title="Image. Max. upload limit (16MB)" style="margin-right:7px; float:left;"><img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHg9IjBweCIgeT0iMHB4IgogICAgIHdpZHRoPSI1MCIgaGVpZ2h0PSI1MCIKICAgICB2aWV3Qm94PSIwIDAgNDggNDgiCiAgICAgc3R5bGU9IjtmaWxsOiMwMDgwMDA7IgogICAgIGNsYXNzPSJpY29uIGljb25zOC1waWN0dXJlIj48ZyBpZD0ic3VyZmFjZTEiPjxwYXRoIHN0eWxlPSIgZmlsbDojRjU3QzAwOyIgZD0iTSA0MCA0MSBMIDggNDEgQyA1LjgwMDc4MSA0MSA0IDM5LjE5OTIxOSA0IDM3IEwgNCAxMSBDIDQgOC44MDA3ODEgNS44MDA3ODEgNyA4IDcgTCA0MCA3IEMgNDIuMTk5MjE5IDcgNDQgOC44MDA3ODEgNDQgMTEgTCA0NCAzNyBDIDQ0IDM5LjE5OTIxOSA0Mi4xOTkyMTkgNDEgNDAgNDEgWiAiPjwvcGF0aD48cGF0aCBzdHlsZT0iIGZpbGw6I0ZGRjlDNDsiIGQ9Ik0gMzggMTYgQyAzOCAxNy42NTYyNSAzNi42NTYyNSAxOSAzNSAxOSBDIDMzLjM0Mzc1IDE5IDMyIDE3LjY1NjI1IDMyIDE2IEMgMzIgMTQuMzQzNzUgMzMuMzQzNzUgMTMgMzUgMTMgQyAzNi42NTYyNSAxMyAzOCAxNC4zNDM3NSAzOCAxNiBaICI+PC9wYXRoPjxwYXRoIHN0eWxlPSIgZmlsbDojOTQyQTA5OyIgZD0iTSAyMCAxNiBMIDkgMzIgTCAzMSAzMiBaICI+PC9wYXRoPjxwYXRoIHN0eWxlPSIgZmlsbDojQkYzNjBDOyIgZD0iTSAzMSAyMiBMIDIzIDMyIEwgMzkgMzIgWiAiPjwvcGF0aD48L2c+PC9zdmc+" alt="Image" style="width:25px; margin-left:50px"></a>
                        </span>
                        <p name="media-input-vertical-1-<?php echo $_SESSION['SessionUserId']; ?>" id="media-input-vertical-1-<?php echo $_SESSION['SessionUserId']; ?>" value=""> </p>
                    </div>
                    <div class="column" style="height: 150px; width: 150px;border:3px solid black;float: left;"> 
                        <span id="media-links-vertical-2-<?php echo $_SESSION['SessionUserId']; ?>">
                            <a align="center" onclick="UploadPictureShow(2,'collag', 'vertical')" href="Javascript:void(0);" title="Image. Max. upload limit (16MB)" style="margin-right:7px; float:left;"><img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHg9IjBweCIgeT0iMHB4IgogICAgIHdpZHRoPSI1MCIgaGVpZ2h0PSI1MCIKICAgICB2aWV3Qm94PSIwIDAgNDggNDgiCiAgICAgc3R5bGU9IjtmaWxsOiMwMDgwMDA7IgogICAgIGNsYXNzPSJpY29uIGljb25zOC1waWN0dXJlIj48ZyBpZD0ic3VyZmFjZTEiPjxwYXRoIHN0eWxlPSIgZmlsbDojRjU3QzAwOyIgZD0iTSA0MCA0MSBMIDggNDEgQyA1LjgwMDc4MSA0MSA0IDM5LjE5OTIxOSA0IDM3IEwgNCAxMSBDIDQgOC44MDA3ODEgNS44MDA3ODEgNyA4IDcgTCA0MCA3IEMgNDIuMTk5MjE5IDcgNDQgOC44MDA3ODEgNDQgMTEgTCA0NCAzNyBDIDQ0IDM5LjE5OTIxOSA0Mi4xOTkyMTkgNDEgNDAgNDEgWiAiPjwvcGF0aD48cGF0aCBzdHlsZT0iIGZpbGw6I0ZGRjlDNDsiIGQ9Ik0gMzggMTYgQyAzOCAxNy42NTYyNSAzNi42NTYyNSAxOSAzNSAxOSBDIDMzLjM0Mzc1IDE5IDMyIDE3LjY1NjI1IDMyIDE2IEMgMzIgMTQuMzQzNzUgMzMuMzQzNzUgMTMgMzUgMTMgQyAzNi42NTYyNSAxMyAzOCAxNC4zNDM3NSAzOCAxNiBaICI+PC9wYXRoPjxwYXRoIHN0eWxlPSIgZmlsbDojOTQyQTA5OyIgZD0iTSAyMCAxNiBMIDkgMzIgTCAzMSAzMiBaICI+PC9wYXRoPjxwYXRoIHN0eWxlPSIgZmlsbDojQkYzNjBDOyIgZD0iTSAzMSAyMiBMIDIzIDMyIEwgMzkgMzIgWiAiPjwvcGF0aD48L2c+PC9zdmc+" alt="Image" style="width:25px; margin-left:50px"></a>
                        </span>
                            <p name="media-input-vertical-2-<?php echo $_SESSION['SessionUserId']; ?>" id="media-input-vertical-2-<?php echo $_SESSION['SessionUserId']; ?>" value=""> </p>
                    </div>
                    <br/> 
                    <input type="button" value="save" onclick="saveGridAndBusinessCardImage('<?php echo $_SESSION['SessionUserId']; ?>', 'collag', 'vertical')">
                </div>
                <div class="row" id="divBusinessCardTemp1" style="height: 100px; width: 300px;margin-left: 300px;" hidden> 
                    <div class="content-container">
                        <div class="inner-square">
                        <span id="media-links-card-temp1-<?php echo $_SESSION['SessionUserId']; ?>">
                        <a align="center" onclick="UploadPictureShow(1, 'card', 'temp1')" href="Javascript:void(0);" title="Image. Max. upload limit (16MB)" style="margin-right:7px; float:left;"><img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHg9IjBweCIgeT0iMHB4IgogICAgIHdpZHRoPSI1MCIgaGVpZ2h0PSI1MCIKICAgICB2aWV3Qm94PSIwIDAgNDggNDgiCiAgICAgc3R5bGU9IjtmaWxsOiMwMDgwMDA7IgogICAgIGNsYXNzPSJpY29uIGljb25zOC1waWN0dXJlIj48ZyBpZD0ic3VyZmFjZTEiPjxwYXRoIHN0eWxlPSIgZmlsbDojRjU3QzAwOyIgZD0iTSA0MCA0MSBMIDggNDEgQyA1LjgwMDc4MSA0MSA0IDM5LjE5OTIxOSA0IDM3IEwgNCAxMSBDIDQgOC44MDA3ODEgNS44MDA3ODEgNyA4IDcgTCA0MCA3IEMgNDIuMTk5MjE5IDcgNDQgOC44MDA3ODEgNDQgMTEgTCA0NCAzNyBDIDQ0IDM5LjE5OTIxOSA0Mi4xOTkyMTkgNDEgNDAgNDEgWiAiPjwvcGF0aD48cGF0aCBzdHlsZT0iIGZpbGw6I0ZGRjlDNDsiIGQ9Ik0gMzggMTYgQyAzOCAxNy42NTYyNSAzNi42NTYyNSAxOSAzNSAxOSBDIDMzLjM0Mzc1IDE5IDMyIDE3LjY1NjI1IDMyIDE2IEMgMzIgMTQuMzQzNzUgMzMuMzQzNzUgMTMgMzUgMTMgQyAzNi42NTYyNSAxMyAzOCAxNC4zNDM3NSAzOCAxNiBaICI+PC9wYXRoPjxwYXRoIHN0eWxlPSIgZmlsbDojOTQyQTA5OyIgZD0iTSAyMCAxNiBMIDkgMzIgTCAzMSAzMiBaICI+PC9wYXRoPjxwYXRoIHN0eWxlPSIgZmlsbDojQkYzNjBDOyIgZD0iTSAzMSAyMiBMIDIzIDMyIEwgMzkgMzIgWiAiPjwvcGF0aD48L2c+PC9zdmc+" alt="Image" style="width:25px; margin-left:50px"></a>
                        </span>
                        <p name="media-input-card-temp1-<?php echo $_SESSION['SessionUserId']; ?>" id="media-input-card-temp1-<?php echo $_SESSION['SessionUserId']; ?>" value=""> </p>
                        </div> 
                    </div>
                    <br/>
                    <input type="button" value="save" onclick="saveGridAndBusinessCardImage('<?php echo $_SESSION['SessionUserId']; ?>', 'card', 'temp1')">
                </div>
                <div class="row" id="divBusinessCardTemp2" style="height: 100px; width: 300px;margin-left: 300px;" hidden> 
                    <div class="content-container">
                        <div class="inner-square1">
                        <span id="media-links-card-temp2-<?php echo $_SESSION['SessionUserId']; ?>">
                        <a align="center" onclick="UploadPictureShow(1, 'card', 'temp2')" href="Javascript:void(0);" title="Image. Max. upload limit (16MB)" style="margin-right:7px; float:left;"><img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHg9IjBweCIgeT0iMHB4IgogICAgIHdpZHRoPSI1MCIgaGVpZ2h0PSI1MCIKICAgICB2aWV3Qm94PSIwIDAgNDggNDgiCiAgICAgc3R5bGU9IjtmaWxsOiMwMDgwMDA7IgogICAgIGNsYXNzPSJpY29uIGljb25zOC1waWN0dXJlIj48ZyBpZD0ic3VyZmFjZTEiPjxwYXRoIHN0eWxlPSIgZmlsbDojRjU3QzAwOyIgZD0iTSA0MCA0MSBMIDggNDEgQyA1LjgwMDc4MSA0MSA0IDM5LjE5OTIxOSA0IDM3IEwgNCAxMSBDIDQgOC44MDA3ODEgNS44MDA3ODEgNyA4IDcgTCA0MCA3IEMgNDIuMTk5MjE5IDcgNDQgOC44MDA3ODEgNDQgMTEgTCA0NCAzNyBDIDQ0IDM5LjE5OTIxOSA0Mi4xOTkyMTkgNDEgNDAgNDEgWiAiPjwvcGF0aD48cGF0aCBzdHlsZT0iIGZpbGw6I0ZGRjlDNDsiIGQ9Ik0gMzggMTYgQyAzOCAxNy42NTYyNSAzNi42NTYyNSAxOSAzNSAxOSBDIDMzLjM0Mzc1IDE5IDMyIDE3LjY1NjI1IDMyIDE2IEMgMzIgMTQuMzQzNzUgMzMuMzQzNzUgMTMgMzUgMTMgQyAzNi42NTYyNSAxMyAzOCAxNC4zNDM3NSAzOCAxNiBaICI+PC9wYXRoPjxwYXRoIHN0eWxlPSIgZmlsbDojOTQyQTA5OyIgZD0iTSAyMCAxNiBMIDkgMzIgTCAzMSAzMiBaICI+PC9wYXRoPjxwYXRoIHN0eWxlPSIgZmlsbDojQkYzNjBDOyIgZD0iTSAzMSAyMiBMIDIzIDMyIEwgMzkgMzIgWiAiPjwvcGF0aD48L2c+PC9zdmc+" alt="Image" style="width:25px; margin-left:50px"></a>
                        </span>
                        <p name="media-input-card-temp2-<?php echo $_SESSION['SessionUserId']; ?>" id="media-input-card-temp2-<?php echo $_SESSION['SessionUserId']; ?>" value=""> </p>
                        </div> 
                    </div>
                    <br/>
                    <input type="button" value="save" onclick="saveGridAndBusinessCardImage('<?php echo $_SESSION['SessionUserId']; ?>', 'card', 'temp2')">
                </div>
                <div class="row" id="divBusinessCalendarTemp1" style="height: 100px; width: 300px;margin-left: 300px;" hidden> 
                    <div class="content-container">
                        <div class="inner-square-calendar">
                        <span id="media-links-calendar-temp1-<?php echo $_SESSION['SessionUserId']; ?>">
                        <a align="center" onclick="UploadPictureShow(1, 'calendar', 'temp1')" href="Javascript:void(0);" title="Image. Max. upload limit (16MB)" style="margin-right:7px; float:left;"><img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHg9IjBweCIgeT0iMHB4IgogICAgIHdpZHRoPSI1MCIgaGVpZ2h0PSI1MCIKICAgICB2aWV3Qm94PSIwIDAgNDggNDgiCiAgICAgc3R5bGU9IjtmaWxsOiMwMDgwMDA7IgogICAgIGNsYXNzPSJpY29uIGljb25zOC1waWN0dXJlIj48ZyBpZD0ic3VyZmFjZTEiPjxwYXRoIHN0eWxlPSIgZmlsbDojRjU3QzAwOyIgZD0iTSA0MCA0MSBMIDggNDEgQyA1LjgwMDc4MSA0MSA0IDM5LjE5OTIxOSA0IDM3IEwgNCAxMSBDIDQgOC44MDA3ODEgNS44MDA3ODEgNyA4IDcgTCA0MCA3IEMgNDIuMTk5MjE5IDcgNDQgOC44MDA3ODEgNDQgMTEgTCA0NCAzNyBDIDQ0IDM5LjE5OTIxOSA0Mi4xOTkyMTkgNDEgNDAgNDEgWiAiPjwvcGF0aD48cGF0aCBzdHlsZT0iIGZpbGw6I0ZGRjlDNDsiIGQ9Ik0gMzggMTYgQyAzOCAxNy42NTYyNSAzNi42NTYyNSAxOSAzNSAxOSBDIDMzLjM0Mzc1IDE5IDMyIDE3LjY1NjI1IDMyIDE2IEMgMzIgMTQuMzQzNzUgMzMuMzQzNzUgMTMgMzUgMTMgQyAzNi42NTYyNSAxMyAzOCAxNC4zNDM3NSAzOCAxNiBaICI+PC9wYXRoPjxwYXRoIHN0eWxlPSIgZmlsbDojOTQyQTA5OyIgZD0iTSAyMCAxNiBMIDkgMzIgTCAzMSAzMiBaICI+PC9wYXRoPjxwYXRoIHN0eWxlPSIgZmlsbDojQkYzNjBDOyIgZD0iTSAzMSAyMiBMIDIzIDMyIEwgMzkgMzIgWiAiPjwvcGF0aD48L2c+PC9zdmc+" alt="Image" style="width:25px; margin-left:50px"></a>
                        </span>
                        <p name="media-input-calendar-temp1-<?php echo $_SESSION['SessionUserId']; ?>" id="media-input-calendar-temp1-<?php echo $_SESSION['SessionUserId']; ?>" value=""> </p>
                        </div> 
                    </div>
                    <br/>
                    <input type="button" value="save" onclick="saveGridAndBusinessCardImage('<?php echo $_SESSION['SessionUserId']; ?>', 'calendar', 'temp1')">
                </div>
                <div class="row" id="divBusinessCalendarTemp2" style="height: 100px; width: 300px;margin-left: 300px;" hidden> 
                    <div class="content-container">
                        <div class="inner-square-calendar1">
                        <span id="media-links-calendar-temp2-<?php echo $_SESSION['SessionUserId']; ?>">
                        <a align="center" onclick="UploadPictureShow(1, 'calendar', 'temp2')" href="Javascript:void(0);" title="Image. Max. upload limit (16MB)" style="margin-right:7px; float:left;"><img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHg9IjBweCIgeT0iMHB4IgogICAgIHdpZHRoPSI1MCIgaGVpZ2h0PSI1MCIKICAgICB2aWV3Qm94PSIwIDAgNDggNDgiCiAgICAgc3R5bGU9IjtmaWxsOiMwMDgwMDA7IgogICAgIGNsYXNzPSJpY29uIGljb25zOC1waWN0dXJlIj48ZyBpZD0ic3VyZmFjZTEiPjxwYXRoIHN0eWxlPSIgZmlsbDojRjU3QzAwOyIgZD0iTSA0MCA0MSBMIDggNDEgQyA1LjgwMDc4MSA0MSA0IDM5LjE5OTIxOSA0IDM3IEwgNCAxMSBDIDQgOC44MDA3ODEgNS44MDA3ODEgNyA4IDcgTCA0MCA3IEMgNDIuMTk5MjE5IDcgNDQgOC44MDA3ODEgNDQgMTEgTCA0NCAzNyBDIDQ0IDM5LjE5OTIxOSA0Mi4xOTkyMTkgNDEgNDAgNDEgWiAiPjwvcGF0aD48cGF0aCBzdHlsZT0iIGZpbGw6I0ZGRjlDNDsiIGQ9Ik0gMzggMTYgQyAzOCAxNy42NTYyNSAzNi42NTYyNSAxOSAzNSAxOSBDIDMzLjM0Mzc1IDE5IDMyIDE3LjY1NjI1IDMyIDE2IEMgMzIgMTQuMzQzNzUgMzMuMzQzNzUgMTMgMzUgMTMgQyAzNi42NTYyNSAxMyAzOCAxNC4zNDM3NSAzOCAxNiBaICI+PC9wYXRoPjxwYXRoIHN0eWxlPSIgZmlsbDojOTQyQTA5OyIgZD0iTSAyMCAxNiBMIDkgMzIgTCAzMSAzMiBaICI+PC9wYXRoPjxwYXRoIHN0eWxlPSIgZmlsbDojQkYzNjBDOyIgZD0iTSAzMSAyMiBMIDIzIDMyIEwgMzkgMzIgWiAiPjwvcGF0aD48L2c+PC9zdmc+" alt="Image" style="width:25px; margin-left:50px"></a>
                        </span>
                        <p name="media-input-calendar-temp2-<?php echo $_SESSION['SessionUserId']; ?>" id="media-input-calendar-temp2-<?php echo $_SESSION['SessionUserId']; ?>" value=""> </p>
                        </div> 
                    </div>
                    <br/>
                    <input type="button" value="save" onclick="saveGridAndBusinessCardImage('<?php echo $_SESSION['SessionUserId']; ?>', 'calendar', 'temp2')">
                </div>
                <div class="modal fade" id="mdlUploadPicture" tabindex="-1" role="dialog" style="z-index: 99999;margin-left: 300px;" hidden>
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h2>UPLOAD PICTURE</h2>
                            </div>
                            <div class="modal-body">
                                <div class="row" id="divUploadImage" >
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" id="btnClose" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                                <button type="button" id="btnSavePhoto" class="btn btn-primary pull-right" onclick="return SubmitCropForm();" style="display: none; border: 1px solid #CCC; background: #CCC" disabled>Save</button>
                            </div>
                        </div>
                    </div>
                </div>
                <br/><br/>
                <div class="row" style="position: auto;bottom: 0;left: 0;width: 300px;top:50px">
                    <?php
                    if (file_exists('photos/' . $_SESSION['SessionUserId'])) {
                        $images = scandir('photos/' . $_SESSION['SessionUserId']);
                        $ignore = array(".", "..");
                        foreach($images as $img)
                        {
                            if ($img == '.' || $img == '..') {
                                continue;
                            }
                            ?>
                            <a href="javascript:void(0);" onclick="ImageBoxShow('<?php echo 'photos/' . $_SESSION['SessionUserId'] . '/'. $img; ?>', '0');">
                            <img src="<?php echo 'photos/' . $_SESSION['SessionUserId'] . '/'. $img; ?>" width='100px' height='100px'><br>
                            </a>
                            <br/><br/>
                            <?php
                        }
                    }
                    ?>
                <div>
                <script type="text/javascript">
                    $("input:radio[name='selecttype']").change(function(){
                        var radioValue = $("input[name='selecttype']:checked").val();
                        if ( radioValue == 'Grid' )
                        {
                            $("#ImageOptionCollageDiv").show();
                            $("#ImageOptionCardDiv").hide();
                            $("#divVerticalGrid").hide();
                            $("#divHorizontalGrid").hide();
                            $("#ImageOptionCalendarDiv").hide();
                        }
                        else if ( radioValue == 'BusinessCard' )
                        {
                            $("#ImageOptionCardDiv").show();
                            $("#ImageOptionCollageDiv").hide();
                            $("#divVerticalGrid").hide();
                            $("#divHorizontalGrid").hide();
                            $("#ImageOptionCalendarDiv").hide();
                        }
                        else
                        {
                            $("#ImageOptionCalendarDiv").show();
                            $("#ImageOptionCardDiv").hide();
                            $("#ImageOptionCollageDiv").hide();
                            $("#divVerticalGrid").hide();
                            $("#divHorizontalGrid").hide();

                        }
                    });
                    function selectImageOption()
                    {
                        var imageOptionName = $('#ImageOptionCollage').find(":selected").val();
                        if ( imageOptionName == 'horizontal' )
                        {
                            $("#divHorizontalGrid").show();
                            $("#divVerticalGrid").hide();
                            $("#divBusinessCardTemp1").hide();
                            $("#divBusinessCardTemp2").hide();
                            $("#divBusinessCalendarTemp1").hide();
                            $("#divBusinessCalendarTemp2").hide();
                        }
                        else
                        {
                            $("#divVerticalGrid").show();
                            $("#divHorizontalGrid").hide();
                            $("#divBusinessCardTemp1").hide();
                            $("#divBusinessCardTemp2").hide();
                            $("#divBusinessCalendarTemp1").hide();
                            $("#divBusinessCalendarTemp2").hide();
                        }
                    }
                    function selectImageOptionCard()
                    {
                        var imageOptionNameCard = $('#ImageOptionCard').find(":selected").val();
                        if ( imageOptionNameCard == 'template1' )
                        {
                            $("#divBusinessCardTemp1").show();
                            $("#divBusinessCardTemp2").hide();
                            $("#divVerticalGrid").hide();
                            $("#divHorizontalGrid").hide();
                            $("#divBusinessCalendarTemp1").hide();
                            $("#divBusinessCalendarTemp2").hide();
                        }
                        else
                        {
                            $("#divBusinessCardTemp2").show();
                            $("#divBusinessCardTemp1").hide();
                            $("#divVerticalGrid").hide();
                            $("#divHorizontalGrid").hide();
                            $("#divBusinessCalendarTemp1").hide();
                            $("#divBusinessCalendarTemp2").hide();
                        }
                    }
                    function selectImageOptionCalendar()
                    {
                        var imageOptionNameCalendar = $('#ImageOptionCalendar').find(":selected").val();
                        if ( imageOptionNameCalendar == 'template1' )
                        {
                            $("#divBusinessCalendarTemp1").show();
                            $("#divBusinessCalendarTemp2").hide();
                            $("#divBusinessCardTemp1").hide();
                            $("#divBusinessCardTemp2").hide();
                            $("#divVerticalGrid").hide();
                            $("#divHorizontalGrid").hide();
                        }
                        else
                        {
                            $("#divBusinessCalendarTemp2").show();
                            $("#divBusinessCalendarTemp1").hide();
                            $("#divBusinessCardTemp2").hide();
                            $("#divBusinessCardTemp1").hide();
                            $("#divVerticalGrid").hide();
                            $("#divHorizontalGrid").hide();
                        }
                    }
                    var reqIdUploadPhoto = 0;
                    function UploadPictureShow(boxNo, isGridorCollag, collagAndCardOpt)
                    {
                        if ( isGridorCollag == 'collag' )
                        {
                            aspectRatio = '';
                            id = collagAndCardOpt + '-' + boxNo + '-' + '<?php echo $_SESSION['SessionUserId']; ?>';
                            if ( collagAndCardOpt == 'horizontal' )
                            {
                                 aspectRatio = '2/1';
                                $('#media-input-' + id).append('<input type="hidden" name="picture-input-' + id + '" id="picture-input-' + id + '" value="" /><img style="width: 295px;height: 145px;' + '" src="" alt="" id="picture-img" name="picture-img">');
                            }
                            else
                            {
                                aspectRatio = '9/16';
                                $('#media-input-' + id).append('<input type="hidden" name="picture-input-' + id + '" id="picture-input-' + id + '" value="" /><img style="width: 145px;height: 145px;' + '" src="" alt="" id="picture-img" name="picture-img">');
                            }
                        }
                        else if ( isGridorCollag == 'card' )
                        {
                            aspectRatio = '1/1';
                            id = isGridorCollag + '-' + collagAndCardOpt + '-' + '<?php echo $_SESSION['SessionUserId']; ?>';
                            $('#media-input-' + id).append('<input type="hidden" name="picture-input-' + id + '" id="picture-input-' + id + '" value="" /><img style="width: 100px;height: 100px;' + '" src="" alt="" id="picture-img" name="picture-img">');
                        }
                        else
                        {
                            aspectRatio = '1/1';
                            id = isGridorCollag + '-' + collagAndCardOpt + '-' + '<?php echo $_SESSION['SessionUserId']; ?>';
                            $('#media-input-' + id).append('<input type="hidden" name="picture-input-' + id + '" id="picture-input-' + id + '" value="" /><img style="width: 100px;height: 100px;' + '" src="" alt="" id="picture-img" name="picture-img">');
                        }
                        $('#media-links-' + id).hide();
                        $('#media-input-' + id).show();
                        $('#picture-img').show();
                        $('#mdlUploadPicture #btnSavePhoto').hide();
                        $('#mdlUploadPicture #btnClose').click(function() {
                            $('#media-links-' + id).show();
                            $('#media-input-' + id + ' [name="picture-input"]').remove();
                            $('#media-input-' + id + ' [name="picture-img"]').remove();
                        });
                        $('#mdlUploadPicture').modal();
                        if ( typeof size === 'undefined' || size === null )
                        {
                            size = '';
                        }
                        var url = 'upload-pictures.php?id=' + id + '&AspectRatio=' + aspectRatio;
                        $('#divUploadImage').html('<div class="text-center"><img src="images/ajax-loading.gif" alt="Loading" /></div>');
                        $.ajax({
                            type : 'get',
                            url : url,
                            beforeSend: function(xhr) {
                                xhr.reqId = ++reqIdUploadPhoto;
                            },
                            success: function(data, status, xhr) {
                                if ( xhr.reqId != reqIdUploadPhoto )
                                {
                                    return false;
                                }
                                $('#divUploadImage').html(data);
                            },
                            error : function() {
                                $('#divUploadImage').html('<div class="text-center">Internet error</div>');
                            }
                        });
                        return false;
                    }

                    function RemovePicture()
                    {
                        document.getElementById("picture-img").src = '';
                        document.getElementById("picture-input").value = 'remove';
                    }

                    $('#mdlUploadPicture').on("hidden.bs.modal", function() {
                        $('#divUploadImage').html('');
                        if ( $('#mdlAddLogOrPlanFood').hasClass('in') )
                        {
                            $('body').addClass('modal-open');
                        }    
                    });

                    function saveGridAndBusinessCardImage(id, isGridorCollag, collagAndCardOpt)
                    {
                        if ( isGridorCollag == 'collag' )
                        {
                            firstImage = $('input#picture-input-' + collagAndCardOpt + '-1-' + id).val();
                            secondImage = $('input#picture-input-' + collagAndCardOpt + '-2-' + id).val();
                            cardImage = '';
                        }
                        else if ( isGridorCollag == 'card' )
                        {
                            firstImage = '';
                            secondImage = '';
                            cardImage = $('input#picture-input-card-' + collagAndCardOpt + '-' + id).val();
                        }
                        else
                        {
                            firstImage = '';
                            secondImage = '';
                            cardImage = $('input#picture-input-calendar-' + collagAndCardOpt + '-' + id).val();
                        }
                        var url = 'generate-image.php?firstImage=' + firstImage + '&secondImage=' + secondImage + '&id=' + id + '&cardImage=' + cardImage + '&isGridorCollag=' + isGridorCollag + '&collagAndCardOpt=' + collagAndCardOpt;
                            $.ajax({
                                type : 'post',
                                url : url,
                                beforeSend: function(xhr) {
                                },
                                success: function(data, status, xhr) {
                                    location.reload(true);
                                },
                                error : function() {
                                    $('#divUploadImage').html('<div class="text-center">Internet error</div>');
                                }
                            });
                            return false;
                    }
                </script>
                <div id="mdlImageBox" class="modal fade" tabindex="1" role="dialog" style="z-index: 99999;">
                    <div id="imgBoxModalWrapper" style="display: table; margin: 0 auto;">
                        <div class="modal-dialog" role="document" style="padding-left: 10px; padding-right: 10px;" id="imgBoxModal">
                            <div class="modal-content">
                                <div class="modal-body" style="padding: 5px;">
                                    <button type="button" class="close" data-dismiss="modal" style="margin-bottom: 10px;">&times;</button>
                                    <img id="imgImageBox" class="img-responsive" alt="" src="" width="100%" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <script type="text/javascript">
                    function ImageBoxShow(url, isHeightFixed)
                    {
                        $('#mdlImageBox #imgImageBox').attr('src', '');
                        $('#mdlImageBox #imgImageBox').attr('src', url);
                        if ( isHeightFixed )
                        {
                            $('#imgBoxModalWrapper').addClass('modal-vartically-center-wraper');
                            $('#imgBoxModal').addClass('modal-vertically-center');
                            $(".img-responsive").addClass("barcode-img");
                        }
                        else
                        {
                            $('#imgBoxModalWrapper').removeClass('modal-vartically-center-wraper');
                            $('#imgBoxModal').removeClass('modal-vertically-center');
                            $(".img-responsive").removeClass("barcode-img");
                        }
                        $('#mdlImageBox').modal();
                        return false;
                    }
                    
                     $("#mdlImageBox").on("hidden.bs.modal", function () {
                        if ( $('#mdlFoodDetails, #mdlActivityDetails, #mdlProfileDetails').hasClass('in') )
                        {
                            $('body').addClass('modal-open');
                        }
                        $('#mdlImageBox #imgImageBox').attr('src', '');
                    });
                </script>
            <?php } ?>
        </div>
    </body>
</html>