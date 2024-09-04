@extends('admin.layouts.template')

@section('content')
        <!-- Content Wrapper. Contains page content -->
      <div id="page_content">
        <div id="page_content_inner">
		<a href="#" class="md-btn" > Add</a>
			<div class="md-card">
                <div class="md-card-content">
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-1-1">
                            <div class="uk-overflow-container"> 
                                <table class="uk-table uk-table-align-vertical listing dt_default">
                                    <thead>
                                        <tr>
                                             <th>Sr#</th>

                                              <th class="nosort">Category</th>
                      
                                              <th class="nosort"> Image</th>
                      
                                             
                      
                                              <th>Active</th>
                                              
                                              <th class="nosort">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
       <!-- /.content-wrapper -->
@endsection