<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
	<div class="content">
		<div class="row">
			<div class="col-md-112">
				<div class="panel_s">
					<div class="panel-body">
						<nav aria-label="breadcrumb">
            				<ol class="breadcrumb custombreadcrumb" style="background-color:#fff !important; margin-Bottom:0px !important;">
            					<li class="breadcrumb-item"><a href="<?= admin_url();?>"><b><i class="fa fa-home fa-fw fa-lg"></i></b></a></li>
            					<li class="breadcrumb-item active text-capitalize"><b>Master</b></li>
            					<li class="breadcrumb-item active" aria-current="page"><b>Traceability</b></li>
							</ol>
						</nav>
						<hr class="hr_style">
						
						<div class="row">
						    <div class="col-md-3">
                                <div class="form-group" app-field-wrapper="PO_EntryNo">
                                    <small class="req text-danger">* </small>
                                    <label for="PO_EntryNo" class="control-label">PO Entry No.</label>
                                    <select id="PO_EntryNo" name="PO_EntryNo" class="form-control selectpicker"  data-live-search="true" title="None Selected" <?= !empty($EditTraceabilitydetails['PurchID']) ? 'disabled' : '' ?>>
                                        <?php foreach ($PoEntryDetails as $row) { ?>
                                            <option value="<?= $row['PurchID']; ?>"
                                                <?= (!empty($EditTraceabilitydetails['PurchID']) 
                                                    && $EditTraceabilitydetails['PurchID'] == $row['PurchID']) 
                                                    ? 'selected' 
                                                    : '' ?>>
                                                <?= $row['PurchID']; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>							
                            </div>
							
							<div class="col-md-3">
								<div class="form-group" app-field-wrapper="PoEntryDate">
								    <small class="req text-danger">* </small>
									<label for="PoEntryDate" class="control-label">PO Entry Date</label>
									<input type="text" id="PoEntryDate" name="PoEntryDate" class="form-control" value="<?= !empty($EditTraceabilitydetails['POEntryDate'])
                                       ? date('d/m/Y', strtotime($EditTraceabilitydetails['POEntryDate']))
                                       : '' ?>" readonly>
								</div>							
							</div>
							
							<div class="col-md-3">
								<div class="form-group" app-field-wrapper="PoNo">
								    <small class="req text-danger">* </small>
									<label for="PoNo" class="control-label">P.O No.</label>
									<input type="text" id="PoNo" name="PoNo" class="form-control" value="<?= !empty($EditTraceabilitydetails['PO_Number'])
                                       ? $EditTraceabilitydetails['PO_Number']
                                       : '' ?>" readonly>
								</div>							
							</div>
							
							<div class="col-md-3">
                                <div class="form-group" app-field-wrapper="vendors">
                                    <small class="req text-danger">* </small>
                                    <label for="vendors" class="control-label"> Vendor Name</label>
                                    <input type="text" id="vendors" name="vendors" class="form-control" value="<?= !empty($EditTraceabilitydetails['company'])
                                       ? $EditTraceabilitydetails['company']
                                       : '' ?>" readonly>
                                </div>							
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group" app-field-wrapper="ItemID">
                                    <small class="req text-danger">* </small>
                                    <label for="ItemID" class="control-label">Item Name</label>
                                    <select id="ItemID" name="ItemID" class="form-control selectpicker"  data-live-search="true" title="None Selected" <?= !empty($EditTraceabilitydetails['ItemID']) ? 'disabled' : '' ?>>
                                        <option value=""></option>
                                    </select>
                                    
                                    <input type="hidden" id="editItemID"
                                           value="<?= !empty($EditTraceabilitydetails['ItemID']) ? $EditTraceabilitydetails['ItemID'] : '' ?>">
                                    
                                    <input type="hidden" id="editItemName"
                                           value="<?= !empty($EditTraceabilitydetails['ItemName']) ? $EditTraceabilitydetails['ItemName'] : '' ?>">
                                </div>							
                            </div>
                            
                            <div class="col-md-3">
                                <div class="form-group" app-field-wrapper="BatchNo">
                                    <small class="req text-danger">* </small>
                                    <label for="BatchNo" class="control-label">Batch.No.</label>
                                    <input type="text" id="BatchNo" name="BatchNo" class="form-control" value="<?= !empty($EditTraceabilitydetails['BatchNo'])
                                       ? $EditTraceabilitydetails['BatchNo']
                                       : '' ?>" readonly>
                                </div>							
                            </div>
                            
                            <div class="col-md-6">
								<div class="form-group" app-field-wrapper="productdetails">
									<label for="productdetails" class="control-label">Product Details</label>
									 <textarea 
                                        id="productdetails" 
                                        name="productdetails" 
                                        class="form-control"
                                        rows="3"><?=
                                            !empty($EditTraceabilitydetails['Product_details'])
                                                ? htmlspecialchars($EditTraceabilitydetails['Product_details'])
                                                : ''
                                        ?>
                                    </textarea>
								</div>							
							</div>
                        </div>
						
                        <hr class="hr_style">
						<div class="row">
							<div class="col-md-12">
								<div class="searchh2" style="display:none;">Please wait fetching data...</div>
								<div class="searchh3" style="display:none;">Please wait Create new product details...</div>
								<div class="searchh4" style="display:none;">Please wait update product details...</div>
							</div>
							<br>
							
							<div class="col-md-12">
                                <h4 class="text-primary" style="margin-bottom:15px;">
                                    Product & Source Details
                                </h4>
                            </div>
                        </div>
                        
                        <div class="row">
							<div class="col-md-2">
								<div class="form-group" app-field-wrapper="ProductName">
									<label for="ProductName" class="control-label">Product Name</label>
									<input type="text" id="ProductName" name="ProductName" class="form-control" value="<?= !empty($EditTraceabilitydetails['ItemName'])
                                       ? $EditTraceabilitydetails['ItemName']
                                       : '' ?>" readonly>
								</div>							
							</div>
							
							<div class="col-md-2">
								<div class="form-group" app-field-wrapper="subgrp1">
									<label for="subgrp1" class="control-label">Sub-Group 1</label>
									<input type="text" id="subgrp1" name="subgrp1" class="form-control" value="<?= !empty($EditTraceabilitydetails['SubGrp1'])
                                       ? $EditTraceabilitydetails['SubGrp1']
                                       : '' ?>" readonly>
								</div>							
							</div>
							
							<div class="col-md-2">
								<div class="form-group" app-field-wrapper="subgrp2">
									<label for="subgrp2" class="control-label">Sub-Group 2</label>
									<input type="text" id="subgrp2" name="subgrp2" class="form-control" value="<?= !empty($EditTraceabilitydetails['SubGrp2'])
                                       ? $EditTraceabilitydetails['SubGrp2']
                                       : '' ?>" readonly>
								</div>							
							</div>
							
							<div class="col-md-3">
								<div class="form-group" app-field-wrapper="Botanicalname">
									<label for="Botanicalname" class="control-label">Botanical Name</label>
									<input type="text" id="Botanicalname" name="Botanicalname" class="form-control" value="<?= !empty($EditTraceabilitydetails['BotanicalName'])
                                       ? $EditTraceabilitydetails['BotanicalName']
                                       : '' ?>" readonly>
								</div>							
							</div>
							
							<div class="col-md-3">
								<div class="form-group" app-field-wrapper="BotanicalSrc">
									<label for="BotanicalSrc" class="control-label">Botanical Source</label>
									<input type="text" id="BotanicalSrc" name="BotanicalSrc" class="form-control" value="<?= !empty($EditTraceabilitydetails['BotanicalSrc'])
                                       ? $EditTraceabilitydetails['BotanicalSrc']
                                       : '' ?>">
								</div>							
							</div>
						</div>
						
					    <div class="clearfix"></div>
					    
					    <div class="row">
					        <div class="col-md-2">
								<div class="form-group" app-field-wrapper="HarvestCordinate">
									<label for="HarvestCordinate" class="control-label">Harvest Coordinates</label>
									<input type="text" id="HarvestCordinate" name="HarvestCordinate" class="form-control" value="<?= !empty($EditTraceabilitydetails['HarvestCordinate'])
                                       ? $EditTraceabilitydetails['HarvestCordinate']
                                       : '' ?>">
								</div>							
							</div>
							
							<div class="col-md-2">
								<div class="form-group" app-field-wrapper="Altitude">
									<label for="Altitude" class="control-label">Altitude</label>
									<input type="text" id="Altitude" name="Altitude" class="form-control" value="<?= !empty($EditTraceabilitydetails['Altitude'])
                                       ? $EditTraceabilitydetails['Altitude']
                                       : '' ?>">
								</div>							
							</div>
							
							<div class="col-md-2">
								<div class="form-group" app-field-wrapper="SoilType">
									<label for="SoilType" class="control-label">Soil Type</label>
									<input type="text" id="SoilType" name="SoilType" class="form-control" value="<?= !empty($EditTraceabilitydetails['Soiltype'])
                                       ? $EditTraceabilitydetails['Soiltype']
                                       : '' ?>">
								</div>							
							</div>
							
					        <div class="col-md-3">
								<div class="form-group" app-field-wrapper="Floraltype">
									<label for="Floraltype" class="control-label">Floral Type</label>
									<input type="text" id="Floraltype" name="Floraltype" class="form-control" value="<?= !empty($EditTraceabilitydetails['Floraltype'])
                                       ? $EditTraceabilitydetails['Floraltype']
                                       : '' ?>">
								</div>							
							</div>
							
							<div class="col-md-3">
								<div class="form-group" app-field-wrapper="BeeSpecies">
									<label for="BeeSpecies" class="control-label">Bee Species</label>
									<input type="text" id="BeeSpecies" name="BeeSpecies" class="form-control" value="<?= !empty($EditTraceabilitydetails['species'])
                                       ? $EditTraceabilitydetails['species']
                                       : '' ?>">
								</div>							
							</div>
						</div>
						<div class="clearfix"></div>
						
						<div class="row">
						    <div class="col-md-2">
								<div class="form-group" app-field-wrapper="HarvestSeason">
									<label for="HarvestSeason" class="control-label">Harvest Season</label>
									<input type="text" id="HarvestSeason" name="HarvestSeason" class="form-control" value="<?= !empty($EditTraceabilitydetails['HarvestSeason'])
                                       ? $EditTraceabilitydetails['HarvestSeason']
                                       : '' ?>">
								</div>							
							</div>
							
							<div class="col-md-2">
								<?php 
								    $value2 = date('d/m/Y');
								    $harvestDate = !empty($EditTraceabilitydetails['HarvestDate'])
                                        ? date('d/m/Y', strtotime($EditTraceabilitydetails['HarvestDate']))
                                        : $value2;
								?>
								<?php echo render_date_input( 'HarvestDate', 'Harvest Date',$harvestDate,'text'); ?>
							</div>
							
						    <div class="col-md-2">
								<div class="form-group" app-field-wrapper="SrcRegion">
									<label for="SrcRegion" class="control-label">Source Region</label>
									<input type="text" id="SrcRegion" name="SrcRegion" class="form-control" value="<?= !empty($EditTraceabilitydetails['Region'])
                                       ? $EditTraceabilitydetails['Region']
                                       : '' ?>">
								</div>							
							</div>
							
							<div class="col-md-3">
								<div class="form-group" app-field-wrapper="Cluster">
									<label for="Cluster" class="control-label">Beekeeper Cluster</label>
									 <textarea 
                                        id="Cluster" 
                                        name="Cluster" 
                                        class="form-control"
                                        rows="3"><?=
                                            !empty($EditTraceabilitydetails['BeekeeperCluster'])
                                                ? htmlspecialchars($EditTraceabilitydetails['BeekeeperCluster'])
                                                : ''
                                        ?>
                                    </textarea>
								</div>							
							</div>
							
							<div class="col-md-3">
								<div class="form-group" app-field-wrapper="Practice">
									<label for="Practice" class="control-label">Farming Practice</label>
									<textarea id="Practice" name="Practice" class="form-control" rows="3"><?= !empty($EditTraceabilitydetails['Practice'])
                                            ? htmlspecialchars(trim($EditTraceabilitydetails['Practice']))
                                            : '' ?>
									</textarea>
								</div>							
							</div>
						</div>
						
						<div class="clearfix"></div>
						<hr class="hr_style">
						
						<div class="row">
						    <div class="col-md-12">
								<div class="searchh21" style="display:none;">Please wait fetching data...</div>
								<div class="searchh22" style="display:none;">Please wait Create new processing details...</div>
								<div class="searchh23" style="display:none;">Please wait update processing details...</div>
							</div>
							<br>
							
						    <div class="col-md-12">
                                <h4 class="text-primary" style="margin-bottom:15px;">
                                    Processing
                                </h4>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-2">
								<div class="form-group" app-field-wrapper="heating">
									<label for="heating" class="control-label">Heating/Boiling</label>
									<input type="text" id="heating" name="heating" class="form-control" value="<?= !empty($EditTraceabilitydetails['HeatBoil'])
                                       ? $EditTraceabilitydetails['HeatBoil']
                                       : '' ?>">
								</div>							
							</div>
							
							<div class="col-md-3">
								<div class="form-group" app-field-wrapper="color">
									<label for="color" class="control-label">Color & Aroma</label>
									<input type="text" id="color" name="color" class="form-control" value="<?= !empty($EditTraceabilitydetails['Aroma'])
                                       ? $EditTraceabilitydetails['Aroma']
                                       : '' ?>">
								</div>							
							</div>
							
							<div class="col-md-3">
                                <div class="form-group">
                                    <label for="trace_pdf">NABL Lab Report</label>
                                    <input 
                                        type="file" 
                                        id="trace_pdf" 
                                        name="trace_pdf" 
                                        class="form-control"
                                        accept="application/pdf">
                                </div>
                            </div>
                            
                            <?php if($EditTraceabilitydetails['trace_pdf']) { ?>
                            <div class="col-md-1 d-flex align-items-start">
                                <div class="form-group" style="margin-top: 17px;"> 
                                    <!-- Button to view PDF -->
                                     <button type="button" id="view_pdf_btn" 
                                            style="background-color: rgba(0, 128, 128, 0.2); 
                                                   border: 1px solid #008080; 
                                                   color: #008080; 
                                                   border-radius: 5px;
                                                   padding: 5px 10px;
                                                   transition: all 0.3s;">
                                        View PDF
                                    </button>
                                </div>
                            </div>
                            <?php } ?>
                            
                            <div class="col-md-3">
								<div class="form-group" app-field-wrapper="storage">
									<label for="storage" class="control-label">Storage After Extraction</label>
									<input type="text" id="storage" name="storage" class="form-control" value="<?= !empty($EditTraceabilitydetails['ExtractionStorage'])
                                       ? $EditTraceabilitydetails['ExtractionStorage']
                                       : '' ?>">
								</div>							
							</div>
						
						</div>
						<div class="clearfix"></div>
					    
					    <div class="row">
					        <div class="col-md-2">
								<div class="form-group" app-field-wrapper="testinglab">
									<label for="testinglab" class="control-label">Testing Lab</label>
									<input type="text" id="testinglab" name="testinglab" class="form-control" value="<?= !empty($EditTraceabilitydetails['TestingLab'])
                                       ? $EditTraceabilitydetails['TestingLab']
                                       : '' ?>">
								</div>							
							</div>
							
							<div class="col-md-3">
								<div class="form-group" app-field-wrapper="extractionmethod">
									<label for="extractionmethod" class="control-label">Extraction Method</label>
									 <textarea 
                                        id="extractionmethod"
                                        name="extractionmethod"
                                        class="form-control"
                                        rows="3"
                                        placeholder="Enter extraction method"><?= !empty($EditTraceabilitydetails['ExtractionMethod'])
                                            ? htmlspecialchars(trim($EditTraceabilitydetails['ExtractionMethod']))
                                            : '' ?>
                                    </textarea>
								</div>							
							</div>
							
							<div class="col-md-4">
								<div class="form-group" app-field-wrapper="filteration">
									<label for="filteration" class="control-label">Filteration Method</label>
									 <textarea 
                                        id="filteration"
                                        name="filteration"
                                        class="form-control"
                                        rows="3"
                                        placeholder="Enter filteration method"><?= !empty($EditTraceabilitydetails['FilterationMethod'])
                                            ? htmlspecialchars(trim($EditTraceabilitydetails['FilterationMethod']))
                                            : '' ?>
                                    </textarea>
								</div>							
							</div>
							
							<div class="col-md-8" id="Qcparameter-wrapper">
							    <?php if (!empty($LabAnalysisDetails)): ?>
							        <?php foreach ($LabAnalysisDetails as $index => $row): ?>
        							    <div class="row parametere_row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label">Parameter Name</label>
                                                    
                                                    <input type="hidden" name="qcparaid[]" class="form-control" value="<?php echo $row['Name'];?>" readonly>
                                                    <input type="text" name="qcparaname[]" class="form-control" value="<?php echo $row['ParameterName'];?>" readonly>
                                                </div>
                                            </div>
                                
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label">Result</label>
                                                    <input type="text" name="qcresult[]" class="form-control" value="<?php echo $row['Value'];?>">
                                                </div>
                                            </div>
                                        </div>
                                <?php endforeach; ?>
                                <?php else: ?>
                                        <div class="row parametere_row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label">Parameter Name</label>
                                                    <input type="text" name="qcparaname[]" class="form-control" value="">
                                                </div>
                                            </div>
                                
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label">Result</label>
                                                    <input type="text" name="qcresult[]" class="form-control" value="">
                                                </div>
                                            </div>
                                        </div>
                                <?php endif; ?>
							</div>
                        
						</div>
						
						<div class="clearfix"></div>
						<hr class="hr_style">
						
						<div class="row">
						    <div class="col-md-12">
								<div class="searchh21" style="display:none;">Please wait fetching data...</div>
								<div class="searchh22" style="display:none;">Please wait Create new shipping details...</div>
								<div class="searchh23" style="display:none;">Please wait update shipping details...</div>
							</div>
							<br>
							
						    <div class="col-md-12">
                                <h4 class="text-primary" style="margin-bottom:15px;">
                                    Shipping
                                </h4>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-2">
								<?php 
								    $value2 = date('d/m/Y');
								    $dispatch_date = !empty($EditTraceabilitydetails['DispatchDate'])
                                        ? date('d/m/Y', strtotime($EditTraceabilitydetails['DispatchDate']))
                                        : $value2;
								?>
								<?php echo render_date_input( 'DispatchDate', 'Dispatch Date',$dispatch_date,'text'); ?>
							</div>
							
							<div class="col-md-2">
								<div class="form-group" app-field-wrapper="Origin">
									<label for="Origin" class="control-label">Origin</label>
									<input type="text" id="Origin" name="Origin" class="form-control" value="<?= !empty($EditTraceabilitydetails['Origin'])
                                       ? $EditTraceabilitydetails['Origin']
                                       : '' ?>">
								</div>							
							</div>
							
							<div class="col-md-3">
								<div class="form-group" app-field-wrapper="destination">
									<label for="destination" class="control-label">Destination</label>
									<input type="text" id="destination" name="destination" class="form-control" value="<?= !empty($EditTraceabilitydetails['Destination'])
                                       ? $EditTraceabilitydetails['Destination']
                                       : '' ?>">
								</div>							
							</div>
							
							<div class="col-md-2">
								<div class="form-group" app-field-wrapper="shippartner">
									<label for="shippartner" class="control-label">Shipping Partner</label>
									<input type="text" id="shippartner" name="shippartner" class="form-control" value="<?= !empty($EditTraceabilitydetails['ShipPartner'])
                                       ? $EditTraceabilitydetails['ShipPartner']
                                       : '' ?>">
								</div>							
							</div>
							
							<div class="col-md-3">
								<div class="form-group" app-field-wrapper="shipmode">
									<label for="shipmode" class="control-label">Shipping Mode</label>
									<input type="text" id="shipmode" name="shipmode" class="form-control" value="<?= !empty($EditTraceabilitydetails['ShipMode'])
                                       ? $EditTraceabilitydetails['ShipMode']
                                       : '' ?>">
								</div>							
							</div>
						</div>
						<div class="clearfix"></div>
						
						<div class="row">
						    <div class="col-md-2">
								<div class="form-group" app-field-wrapper="duration">
									<label for="duration" class="control-label">Transit Duration</label>
									<input type="text" id="duration" name="duration" class="form-control" value="<?= !empty($EditTraceabilitydetails['TransitDuration'])
                                       ? $EditTraceabilitydetails['TransitDuration']
                                       : '' ?>">
								</div>							
							</div>
							
							<div class="col-md-2">
								<div class="form-group" app-field-wrapper="warehousearrival">
									<label for="warehousearrival" class="control-label">Arrival at Warehouse</label>
									<input type="text" id="warehousearrival" name="warehousearrival" class="form-control" value="<?= !empty($EditTraceabilitydetails['ArrivalWarehouse'])
                                       ? $EditTraceabilitydetails['ArrivalWarehouse']
                                       : '' ?>">
								</div>							
							</div>
							
							<div class="col-md-3">
								<div class="form-group" app-field-wrapper="route">
									<label for="route" class="control-label">Transit Route</label>
									<input type="text" id="route" name="route" class="form-control" value="<?= !empty($EditTraceabilitydetails['TransitRoute'])
                                       ? $EditTraceabilitydetails['TransitRoute']
                                       : '' ?>">
								</div>							
							</div>
							
							<div class="col-md-2">
								<div class="form-group" app-field-wrapper="trackid">
									<label for="trackid" class="control-label">Tracking ID</label>
									<input type="text" id="trackid" name="trackid" class="form-control" value="<?= !empty($EditTraceabilitydetails['TrackID'])
                                       ? $EditTraceabilitydetails['TrackID']
                                       : '' ?>">
								</div>							
							</div>
							
							<div class="col-md-3">
								<div class="form-group" app-field-wrapper="transportpack">
									<label for="transportpack" class="control-label">Transport Packaging</label>
									 <textarea 
                                        id="transportpack"
                                        name="transportpack"
                                        class="form-control"
                                        rows="3"><?= !empty($EditTraceabilitydetails['TransportPack'])
                                            ? htmlspecialchars(trim($EditTraceabilitydetails['TransportPack']))
                                            : '' ?>
                                    </textarea>
								</div>							
							</div>
						</div>
						
						<div class="clearfix"></div>
						<hr class="hr_style">
						
						<div class="row">
						    <div class="col-md-12">
								<div class="searchh21" style="display:none;">Please wait fetching data...</div>
								<div class="searchh22" style="display:none;">Please wait Create new packaging details...</div>
								<div class="searchh23" style="display:none;">Please wait update packaging details...</div>
							</div>
							<br>
							
						    <div class="col-md-12">
                                <h4 class="text-primary" style="margin-bottom:15px;">
                                    Packaging
                                </h4>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-2">
								<?php 
								    $value2 = date('d/m/Y');
								    $packaging_date = !empty($EditTraceabilitydetails['PackDate'])
                                        ? date('d/m/Y', strtotime($EditTraceabilitydetails['PackDate']))
                                        : $value2;
								?>
								<?php echo render_date_input( 'PackingDate', 'Packing Date',$packaging_date,'text'); ?>
								<input type="hidden" name="PackingDate" value="">
							</div>
							
							<div class="col-md-3">
								<div class="form-group" app-field-wrapper="packlocation">
									<label for="packlocation" class="control-label">Packaging Location</label>
									<input type="text" id="packlocation" name="packlocation" class="form-control" value="<?= !empty($EditTraceabilitydetails['PackLocation'])
                                       ? $EditTraceabilitydetails['PackLocation']
                                       : '' ?>">
								</div>							
							</div>
							
							<div class="col-md-3">
								<div class="form-group" app-field-wrapper="packtype">
									<label for="packtype" class="control-label">Packaging Type</label>
									<input type="text" id="packtype" name="packtype" class="form-control" value="<?= !empty($EditTraceabilitydetails['PackType'])
                                       ? $EditTraceabilitydetails['PackType']
                                       : '' ?>">
								</div>							
							</div>
							
							<div class="col-md-2">
								<div class="form-group" app-field-wrapper="Unit">
									<label for="Unit" class="control-label">Packaging Unit</label>
									<input type="text" id="Unit" name="Unit" class="form-control" value="<?= !empty($EditTraceabilitydetails['PackUnit'])
                                       ? $EditTraceabilitydetails['PackUnit']
                                       : '' ?>">
								</div>							
							</div>
							
							<div class="col-md-2">
								<div class="form-group" app-field-wrapper="shelflife">
									<label for="shelflife" class="control-label">Shelf Life</label>
									<input type="text" id="shelflife" name="shelflife" class="form-control" value="<?= !empty($EditTraceabilitydetails['ShelfLife'])
                                       ? $EditTraceabilitydetails['ShelfLife']
                                       : '' ?>">
								</div>							
							</div>
						</div>
						<div class="clearfix"></div>
						
						<div class="row">
						    <div class="col-md-2">
								<?php 
								    $value2 = date('d/m/Y');
								    $BeforeDate = !empty($EditTraceabilitydetails['beforedate'])
                                        ? date('d/m/Y', strtotime($EditTraceabilitydetails['beforedate']))
                                        : $value2;
								?>
								<?php echo render_date_input( 'bestbeforedate', 'Best Before Date',$BeforeDate,'text'); ?>
								<input type="hidden" name="bestbeforedate" value="">
							</div>
							
							<div class="col-md-3">
								<div class="form-group" app-field-wrapper="storageconditions">
									<label for="storageconditions" class="control-label">Storage Conditions</label>
									<input type="text" id="storageconditions" name="storageconditions" class="form-control" value="<?= !empty($EditTraceabilitydetails['StorageCond'])
                                       ? $EditTraceabilitydetails['StorageCond']
                                       : '' ?>">
								</div>							
							</div>
							
							<div class="col-md-4">
								<div class="form-group" app-field-wrapper="labeldesc">
									<label for="labeldesc" class="control-label">Label Description</label>
									 <textarea 
                                        id="labeldesc"
                                        name="labeldesc"
                                        class="form-control"
                                        rows="3"><?= !empty($EditTraceabilitydetails['labeldesc'])
                                            ? htmlspecialchars(trim($EditTraceabilitydetails['labeldesc']))
                                            : '' ?>
                                    </textarea>
								</div>							
							</div>
							
							<div class="col-md-3">
								<div class="form-group" app-field-wrapper="packmaterial">
									<label for="packmaterial" class="control-label">Packaging Material</label>
									 <textarea 
                                        id="packmaterial"
                                        name="packmaterial"
                                        class="form-control"
                                        rows="3"><?= !empty($EditTraceabilitydetails['packmaterial'])
                                            ? htmlspecialchars(trim($EditTraceabilitydetails['packmaterial']))
                                            : '' ?>
                                    </textarea>
								</div>							
							</div>
						</div>
						
						<div class="clearfix"></div>
						<hr class="hr_style">
						
						<div class="row">
                            <div class="col-md-6 col-lg-4 mb-3">
                                <div class="card shadow-sm">
                                    <div class="card-body text-left">
                                        
                                        <label for="img_two" class="form-label fw-semibold">
                                            Journey of Product Image
                                        </label>
                        
                                        <input 
                                            type="file" 
                                            class="form-control" 
                                            id="img_two" 
                                            name="img_two"
                                            accept="image/*"
                                        >
                                        
                                        <small class="text-danger d-block mt-2">
                                            Recommended image size: <strong>1920 × 550 pixels</strong> (horizontal banner)
                                        </small>
                                    </div>
                                </div>
                            </div>
                            
                            <?php if (!empty($EditTraceabilitydetails['journeyproductimg'])): 
                                $imgUrl = base_url('uploads/trace-products/' . $EditTraceabilitydetails['journeyproductimg']);
                            ?>
                            <div class="col-md-2 mb-3" style="margin-left:-10px;margin-top:20px;">
                                <button
                                    type="button"
                                    class="btn btn-outline-primary btn-sm"
                                    onclick="window.open('<?= htmlspecialchars($imgUrl) ?>', '_blank')">
                                    View Image
                                </button>
                            </div>
                            <?php endif; ?>
                        </div>
						
						<div class="clearfix"></div>
						<hr class="hr_style">
						
						<div class="row">
						    <div class="col-md-12">
								<div class="searchh21" style="display:none;">Please wait fetching data...</div>
								<div class="searchh22" style="display:none;">Please wait Create Nutritional Value details...</div>
								<div class="searchh23" style="display:none;">Please wait update Nutritional Value details...</div>
							</div>
							<br>
							
						    <div class="col-md-12">
                                <h4 class="text-primary" style="margin-bottom:15px;">
                                    Nutritional Value
                                </h4>
                            </div>
                        </div>
                        
                        <div id="nutrition-wrapper">

                            <?php if (!empty($NutritionalValueDetails)): ?>
                                <?php foreach ($NutritionalValueDetails as $index => $row): ?>
                                    <div class="row nutrition-row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label">Nutritional Information</label>
                                                <input type="text" name="nutrallabel[]" class="form-control" value="<?= $row['name'] ?>">
                                            </div>
                                        </div>
                            
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label">Amount per 100g (Approx Values)</label>
                                                <input type="text" name="nutrivalue[]" class="form-control" value="<?= $row['value'] ?>">
                                            </div>
                                        </div>
                            
                                        <div class="col-md-1 d-flex align-items-end">
                                            <?php if ($index === 0): ?>
                                                <button type="button" class="btn btn-success add-nutrition" style="margin-top:20px;">+</button>
                                            <?php else: ?>
                                                <button type="button" class="btn btn-danger remove-nutrition" style="margin-top:20px;">−</button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <!-- Empty row for create mode -->
                                <div class="row nutrition-row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label">Nutritional Information</label>
                                            <input type="text" name="nutrallabel[]" class="form-control" value="">
                                        </div>
                                    </div>
                            
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label">Amount per 100g (Approx Values)</label>
                                            <input type="text" name="nutrivalue[]" class="form-control" value="">
                                        </div>
                                    </div>
                            
                                    <div class="col-md-1 d-flex align-items-end">
                                        <button type="button" class="btn btn-success add-nutrition" style="margin-top:20px;">+</button>
                                    </div>
                                </div>
                            <?php endif; ?>
                        
                        </div>
						
						<div class="clearfix"></div>
						<hr class="hr_style">
					
					    <br>
					    <div class="row">
						    <div class="col-md-12">
                                <h4 class="text-primary" style="margin-bottom:15px;">
                                    Certificates
                                </h4>
                            </div>
                        </div>
                        
                        <div class="row">
							<div class="col-md-6 col-lg-2 mb-3">
                                <div class="card shadow-sm">
                                    <div class="card-body text-left">
                                        
                                        <label for="fssai" class="form-label fw-semibold">
                                            FSSAI
                                        </label>
                        
                                        <input 
                                            type="file" 
                                            class="form-control" 
                                            id="fssai" 
                                            name="fssai"
                                            accept="image/*,application/pdf"
                                        >
                                        
                                        <small class="text-danger d-block mt-2">
                                            Recommended image size: <strong>1241 × 1754 pixels</strong>
                                        </small>
                                    </div>
                                </div>
                                
                                <?php if(!empty($EditTraceabilitydetails['FSSAI_img'])) { ?>
                                <div class="mt-2">
                                        <a href="<?= base_url('uploads/trace-products/'.$EditTraceabilitydetails['FSSAI_img']); ?>" 
                                           target="_blank" 
                                           class="text-primary"
                                           title="View FSSAI Certificate">
                                           
                                           <i class="fa fa-eye" style="font-size:18px;"></i>
                                        </a>
                                </div>
                                <?php } ?>
                            </div>
                            
                            <div class="col-md-6 col-lg-2 mb-3">
                                <div class="card shadow-sm">
                                    <div class="card-body text-left">
                                        
                                        <label for="usfda" class="form-label fw-semibold">
                                            USFDA
                                        </label>
                        
                                        <input 
                                            type="file" 
                                            class="form-control" 
                                            id="usfda" 
                                            name="usfda"
                                            accept="image/*,application/pdf"
                                        >
                                        
                                        <small class="text-danger d-block mt-2">
                                            Recommended image size: <strong>1241 × 1754 pixels</strong>
                                        </small>
                                    </div>
                                </div>
                                
                                <?php if(!empty($EditTraceabilitydetails['USFDA_img'])) { ?>
                                <div class="mt-2">
                                        <a href="<?= base_url('uploads/trace-products/'.$EditTraceabilitydetails['USFDA_img']); ?>" 
                                           target="_blank" 
                                           class="text-primary"
                                           title="View USFDA Certificate">
                                           
                                           <i class="fa fa-eye" style="font-size:18px;"></i>
                                        </a>
                                </div>
                                <?php } ?>
                            </div>
                            
                            <div class="col-md-6 col-lg-2 mb-3">
                                <div class="card shadow-sm">
                                    <div class="card-body text-left">
                                        
                                        <label for="foodsafetycertificate" class="form-label fw-semibold">
                                            Canada Food & Safety
                                        </label>
                        
                                        <input 
                                            type="file" 
                                            class="form-control" 
                                            id="foodsafetycertificate" 
                                            name="foodsafetycertificate"
                                            accept="image/*,application/pdf"
                                        >
                                        
                                        <small class="text-danger d-block mt-2">
                                            Recommended image size: <strong>1241 × 1754 pixels</strong>
                                        </small>
                                    </div>
                                </div>
                                
                                <?php if(!empty($EditTraceabilitydetails['Food_Safety_img'])) { ?>
                                <div class="mt-2">
                                        <a href="<?= base_url('uploads/trace-products/'.$EditTraceabilitydetails['Food_Safety_img']); ?>" 
                                           target="_blank" 
                                           class="text-primary"
                                           title="View Canada Food & Safety Certificate">
                                           
                                           <i class="fa fa-eye" style="font-size:18px;"></i>
                                        </a>
                                </div>
                                <?php } ?>
                            </div>
                            
                            <div class="col-md-6 col-lg-2 mb-3">
                                <div class="card shadow-sm">
                                    <div class="card-body text-left">
                                        
                                        <label for="apeda" class="form-label fw-semibold">
                                            APEDA
                                        </label>
                        
                                        <input 
                                            type="file" 
                                            class="form-control" 
                                            id="apeda" 
                                            name="apeda"
                                            accept="image/*,application/pdf"
                                        >
                                        
                                        <small class="text-danger d-block mt-2">
                                            Recommended image size: <strong>629 × 889 pixels</strong>
                                        </small>
                                    </div>
                                </div>
                                
                                <?php if(!empty($EditTraceabilitydetails['Apeda_img'])) { ?>
                                <div class="mt-2">
                                        <a href="<?= base_url('uploads/trace-products/'.$EditTraceabilitydetails['Apeda_img']); ?>" 
                                           target="_blank" 
                                           class="text-primary"
                                           title="View APEDA Certificate">
                                           
                                           <i class="fa fa-eye" style="font-size:18px;"></i>
                                        </a>
                                </div>
                                <?php } ?>
                            </div>
                            
                            <div class="col-md-6 col-lg-2 mb-3">
                                <div class="card shadow-sm">
                                    <div class="card-body text-left">
                                        
                                        <label for="impexpgoods" class="form-label fw-semibold">
                                            Import Export Goods
                                        </label>
                        
                                        <input 
                                            type="file" 
                                            class="form-control" 
                                            id="impexpgoods" 
                                            name="impexpgoods"
                                            accept="image/*,application/pdf"
                                        >
                                        
                                        <small class="text-danger d-block mt-2">
                                            Recommended image size: <strong>629 × 889 pixels</strong>
                                        </small>
                                    </div>
                                </div>
                                
                                <?php if(!empty($EditTraceabilitydetails['ImportExport_GoodsImg'])) { ?>
                                <div class="mt-2">
                                        <a href="<?= base_url('uploads/trace-products/'.$EditTraceabilitydetails['ImportExport_GoodsImg']); ?>" 
                                           target="_blank" 
                                           class="text-primary"
                                           title="View Import Export Goods Certificate">
                                           
                                           <i class="fa fa-eye" style="font-size:18px;"></i>
                                        </a>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
						
						<div class="clearfix"></div>
						<br>
						<div class="row sticky-action-bar">
    						<div class="col-md-12 text-center action-buttons">
    							<?php if (has_permission('Source_details', '', 'create')) {
    							?>
    							<button type="button" class="btn btn-success saveBtn" style="margin-right: 25px;">Save Details</button>
    							<?php
    								}else{
    							?>
    							<button type="button" class="btn btn-success saveBtn2 disabled" style="margin-right: 25px;">Save Details</button>
    							<?php
    							}?>
    							
    							<?php if (has_permission('Source_details', '', 'edit')) {
    							?>
    							<button type="button" class="btn btn-success updateBtn" style="margin-right: 25px;">Update</button>
    							<?php
    								}else{
    							?>
    							<button type="button" class="btn btn-success updateBtn2 disabled" style="margin-right: 25px;">Update</button>
    							<?php
    							}?>
    							
    							<span></span><a href="#" class="btn btn-warning edit-new-order">View List</a>
    							
    							<?php if (has_permission('Source_details', '', 'edit') && !empty($EditTraceabilitydetails) && $EditTraceabilitydetails['IsQrGenerate'] == 0) {
    							?>
        							<button type="button" class="btn btn-info generateQr" style="margin-left: 10px;">
                                        Generate QR
                                    </button>
                                    <div id="qrcode" style="display:none;"></div>
                                    
                                <?php } ?>
                                
                                <?php if($EditTraceabilitydetails['IsQrGenerate'] == 1) { 
                                    $batchNo = $EditTraceabilitydetails['BatchNo']; 
                                    $qrFolder = 'uploads/trace-products/' . $batchNo; 
                                    $qrFile = $qrFolder . '/traceQr_' . $batchNo . '.png'; 
                                ?>
                                    
                                    <a href="<?= base_url($qrFile) ?>" download class="btn btn-info">Download QR</a>
                                <?php } ?>
                                
                                <img id="qrImg" src="" style="display:none;">
                                
                                <?php 
                                    if($EditTraceabilitydetails){ 
                                        $batch = $EditTraceabilitydetails['BatchNo'];
                                        $traceLink = "https://earthenconnect.com/trace-product/?batch=" . urlencode($batch);
                                ?>
                                        <button type="button" class="btn btn-success viewTraceability" style="margin-left: 10px;" data-link="<?= $traceLink; ?>">
                                            View Traceability
                                        </button>
                                <?php }
                                ?>
    						</div>
						</div>
						
						<input type="hidden" id="isEditMode"
                            value="<?= !empty($EditTraceabilitydetails['id']) ? 1 : 0 ?>">
						
						<div class="clearfix"></div>
						<!-- Details List Model-->
						
						<div class="modal fade Traceability_List" id="Traceability_List" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static">
							<div class="modal-dialog modal-lg" role="document">
								<div class="modal-content">
									<div class="modal-header" style="padding:5px 10px;">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										<h4 class="modal-title">Traceability Details</h4>
									</div>
									<div class="modal-body" style="padding:0px 5px !important">
										
										<div class="table-Traceability_List tableFixHead2">
											<table class="tree table table-striped table-bordered table-Traceability_List tableFixHead2" id="table_Traceability_List" width="100%">
												<thead>
													<tr>
														<th style="text-align:left;" class="sortablePop">Sr.No.</th>
														<th style="text-align:left;" class="sortablePop">PO Entry No.</th>
														<th style="text-align:left;" class="sortablePop">PO Entry Date</th>
														<th style="text-align:left;" class="sortablePop">PO No.</th>
														<th style="text-align:left;" class="sortablePop">Vendor Name</th>
														<th style="text-align:left;" class="sortablePop">Item Name</th>
														<th style="text-align:left;" class="sortablePop">Batch No</th>
														<th style="text-align:left;" class="sortablePop">Created Date</th>
														<th style="text-align:left;" class="sortablePop">Created By</th>
													</tr>
												</thead>
												<tbody>
													<?php
													    $SrNo = 1;
														foreach ($table_data as $key => $value) {
														?>
														<tr class="get_TraceabilityMaster" data-id="<?php echo $value["id"]; ?>">
															<td><?php echo $SrNo;?></td>
															<td><?php echo $value['PurchID'];?></td>
															<td><?php 
                                                                echo !empty($value['POEntryDate']) 
                                                                    ? date('d/m/Y', strtotime($value['POEntryDate'])) 
                                                                    : '';
                                                            ?></td>
															<td><?php echo $value['PO_Number'];?></td>
															<td><?php echo $value['company'];?></td>
															<td><?php echo $value['ItemName'];?></td>
															<td><?php echo $value['BatchNo'];?></td>
															<td><?php 
                                                                echo !empty($value['TransDate']) 
                                                                    ? date('d/m/Y', strtotime($value['TransDate'])) 
                                                                    : '';
                                                            ?></td>
															<td><?php echo $value['firstname'].' '.$value['lastname'];?></td>
														</tr>
													<?php $SrNo++ ; } ?>
												</tbody>
											</table>   
										</div>
									</div>
									<div class="modal-footer" style="padding:0px;">
										<input type="text" id="myInput1" onkeyup="myFunction2()" placeholder="Search for names.." title="Type in a name" style="float: left;width: 100%;">
									</div>
								</div>
								<!-- /.modal-content -->
							</div>
							<!-- /.modal-dialog -->
						</div>
						<!-- /.modal -->
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php init_tail(); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    $(document).ready(function(){
       
        var isEdit = $('#isEditMode').val() == 1;

        if (isEdit) {
            // EDIT MODE
            $('.updateBtn, .updateBtn2').show();
            $('.saveBtn, .saveBtn2').hide();
        } else {
            // CREATE MODE
            $('.updateBtn, .updateBtn2').hide();
            $('.saveBtn, .saveBtn2').show();
        }
		
	    $('#PO_EntryNo').on('changed.bs.select', function () 
	    {
		    var POEntryNo = $(this).val();
		    var url = "<?php echo admin_url(); ?>Traceability/GetPoEntryDetailsByID";
			jQuery.ajax({
				type: 'POST',
				url:url,
				data: {POEntryNo: POEntryNo},
				dataType:'json',
				success: function(data) {
				    var PoDetails = data.po_details;
				    var ItemDetails = data.history_details;
				    
				    $('#PoNo').val(PoDetails.PO_Number);
                    $('#PoEntryDate').val(PoDetails.Transdate);
                    $('#vendors').val(PoDetails.company);
                    
                    var $itemSelect = $('#ItemID');
                    $itemSelect.empty(); 
                    $itemSelect.append('<option value="">None Selected</option>');
        
                    if (ItemDetails.length > 0) {
                        $.each(ItemDetails, function (index, item) {
                            $itemSelect.append(
                                '<option value="' + item.ItemID + '">' + item.ItemName + '</option>'
                            );
                        });
                    }
                    $itemSelect.selectpicker('refresh');
				}
			});
		});
		
		$('#ItemID').on('changed.bs.select', function () {
		    var ItemID = $(this).val();
		    var PO_No = $('#PoNo').val();
		    var url = "<?php echo admin_url(); ?>Traceability/GetBatchNoByItem";
		    jQuery.ajax({
				type: 'POST',
				url:url,
				data: {ItemID: ItemID,PO_No:PO_No},
				dataType:'json',
				success: function(data) {
				    $("#BatchNo").val(data.batch_no);
				    $("#ProductName").val(data.ItemName);
				    $("#subgrp1").val(data.SubGrp1);
				    $("#subgrp2").val(data.SubGrp2);
				    $("#Botanicalname").val(data.BotanicalName);
				    
				    $('#Qcparameter-wrapper').html('');
                    if (data.ParameterDetails && data.ParameterDetails.length > 0) {
                
                        $.each(data.ParameterDetails, function(index, param) {
                
                            var row = `
                                <div class="row parametere_row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Parameter Name</label>
                                            
                                             <input type="hidden" name="qcparaid[]" value="` + param.ParameterID + `">
                                             
                                            <input type="text" name="qcparaname[]" class="form-control" value="` + param.ParameterName + `" readonly>
                                        </div>
                                    </div>
                
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Result</label>
                                            <input type="text" name="qcresult[]" class="form-control" value="">
                                        </div>
                                    </div>
                                </div>
                            `;
                
                            $('#Qcparameter-wrapper').append(row);
                        });
                
                    } else {
                
                        // If no parameters found, show one empty row
                        var emptyRow = `
                            <div class="row parametere_row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">Parameter Name</label>
                                        <input type="text" name="qcparaname[]" class="form-control">
                                    </div>
                                </div>
                
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">Result</label>
                                        <input type="text" name="qcresult[]" class="form-control">
                                    </div>
                                </div>
                            </div>
                        `;
                
                        $('#Qcparameter-wrapper').append(emptyRow);
                    }
    
				}
			});
		});
		
		$('.saveBtn').on('click', function() 
		{ 
            var fileInput = document.getElementById('trace_pdf');
            var file = fileInput.files[0];
            
            var ImgInput2 = document.getElementById('img_two');
            var Imgfile2 = ImgInput2.files[0];
            
            var ImgFssai = document.getElementById('fssai');
            var Imgfile_Fssai = ImgFssai.files[0];
            
            var ImgUsfda = document.getElementById('usfda');
            var Imgfile_Usfda = ImgUsfda.files[0];
            
            var Imgfoodsafety = document.getElementById('foodsafetycertificate');
            var Imgfile_foodsafety = Imgfoodsafety.files[0];
            
            var Img_Apeda = document.getElementById('apeda');
            var Imgfile_Apeda = Img_Apeda.files[0];
            
            var Img_Goods = document.getElementById('impexpgoods');
            var Imgfile_Goods = Img_Goods.files[0];
        
            var formData = new FormData();
        
            formData.append('POEntryNo', $('#PO_EntryNo').val());
            formData.append('PoEntryDate', $('#PoEntryDate').val());
            formData.append('PO_No', $('#PoNo').val());
            formData.append('Vendor', $('#vendors').val());
            formData.append('ItemID', $('#ItemID').val());
            formData.append('BatchNo', $('#BatchNo').val());
            formData.append('ProductDetails', $('#productdetails').val());

            // Product & Source Details
            formData.append('BotanicalSrc', $('#BotanicalSrc').val());
            formData.append('FloralType', $('#Floraltype').val());
            formData.append('BeeSpecies', $('#BeeSpecies').val());
            formData.append('SrcRegion', $('#SrcRegion').val());
            formData.append('HarvestCordinates', $('#HarvestCordinate').val());
            formData.append('Altitude', $('#Altitude').val());
            formData.append('SoilType', $('#SoilType').val());
            formData.append('HarvestSeason', $('#HarvestSeason').val());
            formData.append('HarvestDate', $('#HarvestDate').val());
            formData.append('Cluster', $('#Cluster').val());
            formData.append('Practice', $('#Practice').val());
        
            // Other fields...
            formData.append('ExtractionStorage', $('#storage').val());
            formData.append('HeatBoil', $('#heating').val());
            formData.append('Aroma', $('#color').val());
            formData.append('TestingLab', $('#testinglab').val());
            formData.append('ExtractionMethod', $('#extractionmethod').val());
            formData.append('FilterationMethod', $('#filteration').val());

            // Shipping details
            formData.append('DispatchDate', $('#DispatchDate').val());
            formData.append('Origin', $('#Origin').val());
            formData.append('Destination', $('#destination').val());
            formData.append('Shippartner', $('#shippartner').val());
            formData.append('ShipMode', $('#shipmode').val());
            formData.append('Transitroute', $('#route').val());
            formData.append('TransitDuration', $('#duration').val());
            formData.append('ArrivalWarehouse', $('#warehousearrival').val());
            formData.append('TrackID', $('#trackid').val());
            formData.append('Transportpack', $('#transportpack').val());
        
            // Packaging details
            formData.append('PackingDate', $('#PackingDate').val());
            formData.append('Unit', $('#Unit').val());
            formData.append('location', $('#packlocation').val());
            formData.append('PackType', $('#packtype').val());
            formData.append('ShelfLife', $('#shelflife').val());
            formData.append('StorageCondition', $('#storageconditions').val());
            formData.append('BeforeDate', $('#bestbeforedate').val());
            formData.append('labeldes', $('#labeldesc').val());
            formData.append('packmaterial', $('#packmaterial').val());
            
            var paraIds = $('input[name="qcparaid[]"]');
            var paraResults = $('input[name="qcresult[]"]');
            
            paraIds.each(function(index) {
                formData.append('qcparaid[]', $(this).val());
                formData.append('qcresult[]', paraResults.eq(index).val());
            });

            // Attach PDF file if selected
            if (file) {
                formData.append('trace_pdf', file);
            }
            
            if(Imgfile2){
                formData.append('img_two', Imgfile2);
            }
            
            if(Imgfile_Fssai){
                formData.append('fssai', Imgfile_Fssai);
            }
            
            if(Imgfile_Usfda){
                 formData.append('usfda', Imgfile_Usfda);
            }
            
            if(Imgfile_foodsafety){
                 formData.append('foodsafetycertificate', Imgfile_foodsafety);
            }
            
            if(Imgfile_Apeda){
                 formData.append('apeda', Imgfile_Apeda);
            }
            
            if(Imgfile_Goods){
                 formData.append('impexpgoods', Imgfile_Goods);
            }
            
            $('input[name="nutrallabel[]"]').each(function (i) {
                formData.append('nutrallabel[]', $(this).val());
            });
            
            $('input[name="nutrivalue[]"]').each(function (i) {
                formData.append('nutrivalue[]', $(this).val());
            });
            
            if (!$('#PO_EntryNo').val()) {
                alert('Select PO Entry No.');
                return;
            }
            else if (!$('#PoNo').val()) {
                alert('Select PO No.');
                return;
            }
            else if(!$('#vendors').val()) {
                alert('Select Vendor');
                return;
            }
            else if (!$('#ItemID').val()) {
                alert('Select Item');
                return;
            }
            else if (!$('#BatchNo').val()) {
                alert('Batch No is required.');
                return;
            }
            else
            {
                $.ajax({
                    url: "<?= admin_url() ?>Traceability/SaveTraceabilityDetails",
                    type: "POST",
                    data: formData,
                    contentType: false,  
                    processData: false,  
                    dataType: "JSON",
                    beforeSend: function () {
                        $('.searchh3').css('display','block').css('color','blue');
                    },
                    complete: function () {
                        $('.searchh3').css('display','none');
                    },
                    success: function(res) {
                        if (res.status === true) {
                            alert_float('success', 'Record created successfully...');
                            setTimeout(function() { location.reload(); }, 2000);
                        } else {
                            alert_float('warning', 'Something went wrong...');
                        }
                    }
                
                });
            }
    });
		
		$('.updateBtn').on('click', function() 
		{
            var fileInput = document.getElementById('trace_pdf');
            var file = fileInput.files[0];
            
            var ImgInput2 = document.getElementById('img_two');
            var Imgfile2 = ImgInput2.files[0];
            
            var ImgFssai = document.getElementById('fssai');
            var Imgfile_Fssai = ImgFssai.files[0];
            
            var ImgUsfda = document.getElementById('usfda');
            var Imgfile_Usfda = ImgUsfda.files[0];
            
            var Imgfoodsafety = document.getElementById('foodsafetycertificate');
            var Imgfile_foodsafety = Imgfoodsafety.files[0];
            
            var Img_Apeda = document.getElementById('apeda');
            var Imgfile_Apeda = Img_Apeda.files[0];
            
            var Img_Goods = document.getElementById('impexpgoods');
            var Imgfile_Goods = Img_Goods.files[0];
        
            var formData = new FormData();
            var id = <?= (int) $this->uri->segment(4); ?>;
        
            formData.append('ID', id);
            formData.append('POEntryNo', $('#PO_EntryNo').val());
            formData.append('PoEntryDate', $('#PoEntryDate').val());
            formData.append('PO_No', $('#PoNo').val());
            formData.append('Vendor', $('#vendors').val());
            formData.append('ItemID', $('#ItemID').val());
            formData.append('BatchNo', $('#BatchNo').val());
            formData.append('ProductDetails', $('#productdetails').val());
        
            // Product & Source Details
            formData.append('BotanicalSrc', $('#BotanicalSrc').val());
            formData.append('FloralType', $('#Floraltype').val());
            formData.append('BeeSpecies', $('#BeeSpecies').val());
            formData.append('SrcRegion', $('#SrcRegion').val());
            formData.append('HarvestCordinates', $('#HarvestCordinate').val());
            formData.append('Altitude', $('#Altitude').val());
            formData.append('SoilType', $('#SoilType').val());
            formData.append('HarvestSeason', $('#HarvestSeason').val());
            formData.append('HarvestDate', $('#HarvestDate').val());
            formData.append('Cluster', $('#Cluster').val());
            formData.append('Practice', $('#Practice').val());
        
            // Other fields...
            formData.append('ExtractionStorage', $('#storage').val());
            formData.append('HeatBoil', $('#heating').val());
            formData.append('Aroma', $('#color').val());
            formData.append('TestingLab', $('#testinglab').val());
            formData.append('ExtractionMethod', $('#extractionmethod').val());
            formData.append('FilterationMethod', $('#filteration').val());
        
            // Shipping details
            formData.append('DispatchDate', $('#DispatchDate').val());
            formData.append('Origin', $('#Origin').val());
            formData.append('Destination', $('#destination').val());
            formData.append('Shippartner', $('#shippartner').val());
            formData.append('ShipMode', $('#shipmode').val());
            formData.append('Transitroute', $('#route').val());
            formData.append('TransitDuration', $('#duration').val());
            formData.append('ArrivalWarehouse', $('#warehousearrival').val());
            formData.append('TrackID', $('#trackid').val());
            formData.append('Transportpack', $('#transportpack').val());
        
            // Packaging details
            formData.append('PackingDate', $('#PackingDate').val());
            formData.append('Unit', $('#Unit').val());
            formData.append('location', $('#packlocation').val());
            formData.append('PackType', $('#packtype').val());
            formData.append('ShelfLife', $('#shelflife').val());
            formData.append('StorageCondition', $('#storageconditions').val());
            formData.append('BeforeDate', $('#bestbeforedate').val());
            formData.append('labeldes', $('#labeldesc').val());
            formData.append('packmaterial', $('#packmaterial').val());
            
            var paraIds = $('input[name="qcparaid[]"]');
            var paraResults = $('input[name="qcresult[]"]');
            
            paraIds.each(function(index) {
                formData.append('qcparaid[]', $(this).val());
                formData.append('qcresult[]', paraResults.eq(index).val());
            });

            // Attach PDF file if selected
            if (file) {
                formData.append('trace_pdf', file);
            }
            
            if(Imgfile2){
                formData.append('img_two', Imgfile2);
            }
            
            if(Imgfile_Fssai){
                formData.append('fssai', Imgfile_Fssai);
            }
            
            if(Imgfile_Usfda){
                 formData.append('usfda', Imgfile_Usfda);
            }
            
            if(Imgfile_foodsafety){
                 formData.append('foodsafetycertificate', Imgfile_foodsafety);
            }
            
            if(Imgfile_Apeda){
                 formData.append('apeda', Imgfile_Apeda);
            }
            
            if(Imgfile_Goods){
                 formData.append('impexpgoods', Imgfile_Goods);
            }
            
            $('input[name="nutrallabel[]"]').each(function (i) {
                formData.append('nutrallabel[]', $(this).val());
            });
            
            $('input[name="nutrivalue[]"]').each(function (i) {
                formData.append('nutrivalue[]', $(this).val());
            });
            
            if (!$('#PO_EntryNo').val()) {
                alert('Select PO Entry No.');
                return;
            }
            else if (!$('#PoNo').val()) {
                alert('Select PO No.');
                return;
            }
            else if(!$('#vendors').val()) {
                alert('Select Vendor');
                return;
            }
            else if (!$('#ItemID').val()) {
                alert('Select Item');
                return;
            }
            else if (!$('#BatchNo').val()) {
                alert('Batch No is required.');
                return;
            }
            else
            {
                $.ajax({
                    url: "<?= admin_url() ?>Traceability/UpdateTraceabilityDetails",
                    type: "POST",
                    data: formData,
                    contentType: false,  
                    processData: false,  
                    dataType: "JSON",
                    beforeSend: function () {
                        $('.searchh4').css('display', 'block').css('color', 'blue');
                    },
                    complete: function () {
                        $('.searchh4').css('display', 'none');
                    },
                    success: function(res) {
                        if (res.status === true) {
                            alert_float('success', 'Record updated successfully...');
                            setTimeout(function() { location.reload(); }, 2000);
                        } else {
                            alert_float('warning', 'Something went wrong...');
                        }
                    }
                });
            }
        });
        
        $('.generateQr').on('click', function () {
            var id = <?= (int) $this->uri->segment(4); ?>;
            var batchNo = $('#BatchNo').val();
            if (!batchNo) {
                alert('Batch No is required');
                return;
            }
        
            $('#qrcode').empty();
            $('#qrcode').hide(); // make it visible temporarily
        
            new QRCode(document.getElementById("qrcode"), {
                text: 'https://earthenconnect.com/trace-product/?batch=' + encodeURIComponent(batchNo),
                width: 500,
                height: 500,
                correctLevel: QRCode.CorrectLevel.H
            });
        
            setTimeout(function () {
                let imgData = null;
                let canvas = document.querySelector('#qrcode canvas');
                if (canvas) imgData = canvas.toDataURL('image/png');
        
                let img = document.querySelector('#qrcode img');
                if (!imgData && img) imgData = img.src;
        
                if (!imgData) {
                    alert('QR generation failed');
                    return;
                }
        
                // Save image for download
                $('#qrImg').attr('src', imgData);
        
                $.ajax({
                    url: '<?= admin_url("Traceability/save_qr") ?>',
                    type: 'POST',
                    data: {
                        image: imgData,
                        batchNo: batchNo,
                        id: id
                    },
                    dataType: 'json',
                    success: function (res) {
                        alert(res.message);
                        setTimeout(function() { location.reload(); }, 1000);
                    }
                });
            }, 400);
        });
        
        $('.viewTraceability').on('click', function () {
            var link = $(this).data('link');  
            if (link) {
                window.open(link, '_blank');  
            } else {
                alert('Traceability link not available');
            }
        });
        
       $(document).on('click', '.downloadQr', function () {
            var qrUrl = $(this).data('path'); 
            var batchNo = '<?= $batchNo ?>';  
        
            if (!qrUrl) {
                alert('QR file not found');
                return;
            }
        
            var img = new Image();
            img.crossOrigin = 'anonymous'; 
            img.onload = function () {
                
                var canvas = document.createElement('canvas');
                canvas.width = 500;
                canvas.height = 500;
                var ctx = canvas.getContext('2d');
        
                // Draw the image into the canvas (resized to 500x500)
                ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
        
                // Convert to PNG
                var resizedData = canvas.toDataURL('image/png');
        
                // Trigger download
                var link = document.createElement('a');
                link.href = resizedData;
                link.download = 'traceQr_' + batchNo + '.png';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            };
            img.src = qrUrl;
        });
	});
	
	$('#view_pdf_btn').on('click', function() {
        var pdfFileName = '<?= !empty($EditTraceabilitydetails["trace_pdf"]) ? $EditTraceabilitydetails["trace_pdf"] : "" ?>';

        if(pdfFileName) {
            var pdfUrl = '<?= base_url("uploads/trace-products/") ?>' + pdfFileName;
            window.open(pdfUrl, '_blank'); 
        } else {
            alert('No PDF uploaded for this record.');
        }
    });
    
</script>

<script>
document.addEventListener('click', function (e) {

    if (e.target.classList.contains('add-nutrition')) {

        const row = e.target.closest('.nutrition-row');
        const clone = row.cloneNode(true);
        
        clone.querySelectorAll('input').forEach(input => input.value = '');
        
        const btn = clone.querySelector('.add-nutrition');
        btn.textContent = '−';
        btn.classList.remove('btn-success', 'add-nutrition');
        btn.classList.add('btn-danger', 'remove-nutrition');

        document.getElementById('nutrition-wrapper').appendChild(clone);
    }

    if (e.target.classList.contains('remove-nutrition')) {
        e.target.closest('.nutrition-row').remove();
    }

});

document.addEventListener('click', function (e) {

    if (e.target.classList.contains('add-labanalysis')) {

        const row = e.target.closest('.labanalysis-row');
        const clone = row.cloneNode(true);
        
        clone.querySelectorAll('input').forEach(input => input.value = '');
        
        const btn = clone.querySelector('.add-labanalysis');
        btn.textContent = '−';
        btn.classList.remove('btn-success', 'add-labanalysis');
        btn.classList.add('btn-danger', 'remove-labanalysis');

        document.getElementById('labanalysis-wrapper').appendChild(clone);
    }

    if (e.target.classList.contains('remove-labanalysis')) {
        e.target.closest('.labanalysis-row').remove();
    }

});

</script>

<script>
$(document).on('click', '.edit-new-order', function (e) {
    e.preventDefault();   
    $('#Traceability_List').modal('show');
});

$(document).on('click', '.get_TraceabilityMaster', function () {

    var id = $(this).data('id'); 
    $('#Traceability_List').modal('hide');
    
    setTimeout(function () {
        window.location.href = "<?php echo admin_url('Traceability/AddEditProductSrcDetails/'); ?>" + id;
    }, 300);
});

$(document).ready(function () {

    var editItemID   = $('#editItemID').val();
    var editItemName = $('#editItemName').val();

    if (editItemID) {
        $('#ItemID')
            .append('<option value="' + editItemID + '" selected>' + editItemName + '</option>')
            .prop('disabled', true)
            .selectpicker('refresh');
    }

});

</script>

<script>
    function myFunction2() {
    var input = document.getElementById("myInput1");
    var filter = input.value.toUpperCase();
    var table = document.getElementById("table_Traceability_List");
    var tr = table.getElementsByTagName("tr");

    for (var i = 1; i < tr.length; i++) { 
        var tds = tr[i].getElementsByTagName("td");
        var found = false;

        for (var j = 0; j < tds.length; j++) {
            var txtValue = tds[j].textContent || tds[j].innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                found = true;
                break;
            }
        }

        tr[i].style.display = found ? "" : "none";
    }
}
</script>
<script>
    function isNumber(evt) {
		evt = (evt) ? evt : window.event;
		var charCode = (evt.which) ? evt.which : evt.keyCode;
		if (charCode = 46 && charCode > 31 
		&& (charCode < 48 || charCode > 57)){
			return false;
		}
		return true;
	}
</script>
<style>

    .sticky-action-bar {
        position: sticky;
        bottom: 0;
        background: #fff;
        padding: 15px 0;
        border-top: 1px solid #ddd;
        z-index: 100;
    }
    
    .action-buttons {
        width: 100%;
    }
	
	#item_code1 {
    text-transform: uppercase;
	}
	#table_Traceability_List td:hover {
    cursor: pointer;
	}
	#table_Traceability_List tr:hover {
    background-color: #ccc;
	}
	
    .table-PointMaster_List          { overflow: auto;max-height: 65vh;width:100%;position:relative;top: 0px; }
    .table-PointMaster_List thead th { position: sticky; top: 0; z-index: 1; }
    .table-PointMaster_List tbody th { position: sticky; left: 0; }
    table  { border-collapse: collapse; width: 100%; }
    th, td { padding: 1px 5px !important; white-space: nowrap; border:1px solid !important;font-size:11px; line-height:1.42857143!important;vertical-align: middle !important;}
    th     { background: #50607b;
    color: #fff !important; }
</style>