<!DOCTYPE HTML>
<html>
<head>
	<title>Adddress Book Project</title>
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
<a class="brand" href="#">My Address book</a>
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
 
            <h2>CI Addressbook Import</h2>
                <form method="post" action="<?php echo base_url() ?>test_csv/importcsv" enctype="multipart/form-data">
                    <input type="file" name="userfile" ><br><br>
                    <input type="submit" name="submit" value="UPLOAD" class="btn btn-primary">
                </form>
 
            <br><br>
            <table class="table table-striped table-hover table-bordered">
                <caption>Address Book List</caption>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Departemen</th>
                        <th>Asal</th>
                        <th>Umur</th>
                        <th>Hobi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($data_csv == FALSE): ?>
                        <tr><td colspan="4">There are currently No Addresses</td></tr>
                    <?php else: ?>
                        <?php foreach ($data_csv as $row): ?>
                            <tr>
                                <td><?php echo $row['ID']; ?></td>
                                <td><?php echo $row['NAMA']; ?></td>
                                <td><?php echo $row['DEPARTEMEN']; ?></td>
                                <td><?php echo $row['ASAL']; ?></td>
                                <td><?php echo $row['UMUR']; ?></td>
                                <td><?php echo $row['HOBI']; ?></td>
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