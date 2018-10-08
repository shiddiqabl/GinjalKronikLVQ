<!DOCTYPE HTML>
<html>
<head>
	<title>Data Pasien</title>
</head>

<body>

<div class="navbar navbar-inverse navbar-fixed-top">
<div class="navbar-inner">
<div class="container">
<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
<span class="icon-bar"></span>
<span class="icon-bar"></span>
<span class="icon-bar"></span>
</a>
<a class="brand" href="#">Data Pasien</a>
<div class="nav-collapse collapse">
<ul class="nav">
<li class="active"><a href="<?php echo base_url(); ?>"><i class="icon-home"></i>Home</a></li>
<li><a href="#about">About</a></li>
</ul>
</div><!--/.nav-collapse -->
</div>
</div>
</div>

<div class="container" style="margin-top:50px">
<br>

<?php if (isset($error)): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            <?php if ($this->session->flashdata('success') == TRUE): ?>
                <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
            <?php endif; ?>
 
            <h2>Data Pasien</h2>
                <form method="post" action="<?php echo base_url() ?>import_data/importcsv" enctype="multipart/form-data">
                    <input type="file" name="userfile" ><br><br>
                    <input type="submit" name="submit" value="UPLOAD" class="btn btn-primary">
                </form>
 
            <br><br>
            <table class="table table-striped table-hover table-bordered">
                <caption>Data Pasien</caption>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Age</th>
                        <th>Blood Pressure</th>
                        <th>Specific Albumin</th>
                        <th>Sugar</th>
                        <th>RBC</th>
                        <th>Pus Cell</th>
                        <th>Pus Cell Clumps</th>
                        <th>Bacteria</th>
                        <th>BGR</th>
                        <th>Blood Urea</th>
                        <th>Serum Cretinine</th>
                        <th>Sodium</th>
                        <th>Potassium</th>
                        <th>Hemoglobin</th>
                        <th>PCV</th>
                        <th>WBC Count</th>
                        <th>RBC Count</th>
                        <th>Hypertension</th>
                        <th>DM</th>
                        <th>CAD</th>
                        <th>Appetite</th>
                        <th>Pedal Edema</th>
                        <th>Anemia</th>
                        <th>Class</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($data_pasien == FALSE): ?>
                        <tr><td colspan="4">There are currently No Addresses</td></tr>
                    <?php else: ?>
                        <?php foreach ($data_pasien as $row): ?>
                            <tr>
                                <td><?php echo $row['ID']; ?></td>
                                <td><?php echo $row['AGE']; ?></td>
                                <td><?php echo $row['BP']; ?></td>
                                <td><?php echo $row['SG']; ?></td>
                                <td><?php echo $row['AL']; ?></td>
                                <td><?php echo $row['SU']; ?></td>
                                <td><?php echo $row['RBC']; ?></td>
                                <td><?php echo $row['PC']; ?></td>
                                <td><?php echo $row['PCC']; ?></td>
                                <td><?php echo $row['BA']; ?></td>
                                <td><?php echo $row['BGR']; ?></td>
                                <td><?php echo $row['BU']; ?></td>
                                <td><?php echo $row['SC']; ?></td>
                                <td><?php echo $row['SOD']; ?></td>
                                <td><?php echo $row['POT']; ?></td>
                                <td><?php echo $row['HEMO']; ?></td>
                                <td><?php echo $row['PCV']; ?></td>
                                <td><?php echo $row['WBCC']; ?></td>
                                <td><?php echo $row['RBCC']; ?></td>
                                <td><?php echo $row['HTN']; ?></td>
                                <td><?php echo $row['DM']; ?></td>
                                <td><?php echo $row['CAD']; ?></td>
                                <td><?php echo $row['APPET']; ?></td>
                                <td><?php echo $row['PE']; ?></td>
                                <td><?php echo $row['ANE']; ?></td>
                                <td><?php echo $row['CLASS']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
 
 
            <hr>
            <footer>
                <p>&copy;My Address Book</p>
            </footer>
 
        </div>
 
 
 
    </body>
</html>