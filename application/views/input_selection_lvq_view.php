		<div id="page-wrapper">
			<div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Masukkan Parameter Uji LVQ2</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
            	<div class="col-lg-12">
                    <div class="panel panel-default">
                    	<div class="panel-heading">
                           Form Parameter Uji LVQ2
                       	</div>
                       	<div class="panel-body">                       		
                       		<div class="row">
                       		<div class="col-lg-6">							
								<form  action="<?php echo base_url().'lvq2_controller/lvq2_selection'; ?>" method="post" role="form">									
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
									<div class="form-group">                                    	
                                       	<label>Atribut yang dipilih</label>
                                       	<div class="checkbox">
                                        	<label>
                                            	<input type="checkbox" name="atribut[]" value="ID" checked> 1.ID     
                                            </label>
                                        </div>	
                                       	<div class="checkbox">
                                        	<label>
                                            	<input type="checkbox" name="atribut[]" value="AGE"> 2.AGE     
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                        		<input type="checkbox" name="atribut[]" value="BP"> 3.BP / Blood Pressure
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                        	<label>
                                            	<input type="checkbox" name="atribut[]" value="SG"> 4.SG /Specific Gravity
                                            </label>
                                       	</div>
                                       	<div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="atribut[]" value="AL"> 5.AL / Albumin
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="atribut[]" value="SU"> 6.SU / Sugar
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                            	<input type="checkbox" name="atribut[]" value="RBC"> 7.RBC / Red Blood Cells
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                            	<input type="checkbox" name="atribut[]" value="PC"> 8.PC / Pus Cells
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                            	<input type="checkbox" name="atribut[]" value="PCC"> 9.PCC / Pus Cells Clumps
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                            	<input type="checkbox" name="atribut[]" value="BA"> 10.BA / Bacteria
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                            	<input type="checkbox" name="atribut[]" value="BGR"> 11.BGR / Blood Glucose Random
                                            </label>
                                        </div>              
                                        <div class="checkbox">
                                            <label>
                                            	<input type="checkbox" name="atribut[]" value="BU"> 12.BU / Blood Urea
                                            </label>
                                        </div>             
                                        <div class="checkbox">
                                            <label>
                                            	<input type="checkbox" name="atribut[]" value="SC"> 13. SC / Serum Cretanine
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                            	<input type="checkbox" name="atribut[]" value="SOD"> 14.SOD / Sodium
                                            </label>
                                        </div>  
                                        <div class="checkbox">
                                            <label>
                                            	<input type="checkbox" name="atribut[]" value="POT"> 15.POT / Potassium
                                            </label>
                                        </div> 
                                        <div class="checkbox">
                                            <label>
                                            	<input type="checkbox" name="atribut[]" value="HEMO"> 16.HEMO / Hemoglobin
                                            </label>
                                        </div> 
                                        <div class="checkbox">
                                            <label>
                                            	<input type="checkbox" name="atribut[]" value="PCV"> 17.PCV / Packed Cell Volume 
                                            </label>
                                        </div> 
                                        <div class="checkbox">
                                            <label>
                                            	<input type="checkbox" name="atribut[]" value="WBCC"> 18.WBCC / White Blood Cell Count
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                            	<input type="checkbox" name="atribut[]" value="RBCC"> 19.RBCC / Red Blood Cell Count
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                            	<input type="checkbox" name="atribut[]" value="HTN"> 20.HTN / Hypertension
                                            </label>
                                        </div>   
                                        <div class="checkbox">
                                            <label>
                                            	<input type="checkbox" name="atribut[]" value="DM"> 21.DM / Diabetes Mellitus
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                            	<input type="checkbox" name="atribut[]" value="CAD"> 22.CAD / Coronary Artery Disease
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                            	<input type="checkbox" name="atribut[]" value="APPET"> 23.APPET / Appetite
                                            </label>
                                        </div>   
                                        <div class="checkbox">
                                            <label>
                                            	<input type="checkbox" name="atribut[]" value="PE"> 24.PE / Pedal Enema
                                            </label>
                                        </div> 
                                        <div class="checkbox">
                                            <label>
                                            	<input type="checkbox" name="atribut[]" value="ANE"> 25.ANE / Anemia
                                            </label>
                                        </div> 
                                        <div class="checkbox">
                                            <label>
                                            	<input type="checkbox" name="atribut[]" value="CLASS" checked> 26.CLASS
                                            </label>
                                        </div> 
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


