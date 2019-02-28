		<div id="page-wrapper">
			<div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Masukkan parameter uji LVQ</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
            	<div class="col-lg-12">
                    <div class="panel panel-default">
                    	<div class="panel-heading">
                           Form Parameter Uji LVQ
                       	</div>
                       	<div class="panel-body">
                       		<div class="row">
                       		<div class="col-lg-6">							
								<form  action="<?php echo base_url().'lvq2_controller/lvq2'; ?>" method="post" role="form">									
									<div class="form-group">
										<label  for="ALPHA">Nilai laju pembelajaran / Alpha</label>
										<input class="form-control" id="alpha" name="ALPHA" placeholder="Laju pembelajaran antara 0 hingga 1" autofocus required>																											
									</div>										
									<div class="form-group">
										<label for="EPSILON">Batas nilai pembelajaran / Epsilon</label>								
										<input class="form-control" id="epsilon" name="EPSILON" placeholder="Nilai Epsilon harus lebih kecil dari nilai Alpha" maxlength="30" required>	
									</div>
									<div class="form-group">
										<label for="MAX_EPOCH">Maksimum Epoch</label>								
										<input class="form-control" id="max_epoch" name="MAX_EPOCH" placeholder="Nilai Maksimum Epoch harus lebih dari 1" maxlength="30">	
									</div>																							
									<input class="btn btn-primary" type="submit" value="Mulai pengujian">
									<a href="<?php echo base_url();?>lvq2_controller/index" class="btn btn-danger" role="button">Batal</a>
								</form>				
							</div>
							</div>
                            <!-- /.row (nested) -->
						</div>
                        <!-- /.panel-body -->
					</div>
                    <!-- /.panel -->
                </div>
			</div>    
            <!-- /.row -->
            
            <script>
            $(document).ready( function (){
                $("#epsilon").change(function() {
                	var alpha = $("#alpha").val();
                	var epsilon = $("#epsilon").val();

                	if (epsilon > alpha){
						alert("Nilai Epsilon harus lebih kecil dari nilai Alpha");	
                   	}  
                });
                
                $("#max_epoch").change(function() {
                	var max_epoch = $("#max_epoch").val();                	

                	if (max_epoch < 1){
						alert("Nilai Max Epoch harus lebih dari 1");	
                   	}  
                });
            });
            </script>
            
		</div>
        <!-- /#page-wrapper -->       


