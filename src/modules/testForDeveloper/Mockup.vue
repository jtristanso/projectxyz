
<template>
  <div>
    <div class='row'>
      <div class="col-sm-6">
        <h2>Customers</h2>
      </div>
      <div class="col-sm-6 text-right">
        <button class="btn btn-outline-primary"><i class="fa fa-print" aria-hidden="true"></i> Print</button>
        <button class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> Create</button>
        <button class="btn btn-secondary"><i class="fa fa-refresh" aria-hidden="true"></i> Refresh</button>
      </div>
      <div class="col-sm-12">

        <div class="mb-5 mt-5">
          <form class="form-inline">
            <label class="mr-sm-2" for="inlineFormCustomSelect">Code</label>
            <input class="custom-select mb-2 mr-sm-2 mb-sm-0" placeholder="Code">
            <label class="mr-sm-2" for="inlineFormCustomSelect">Name</label>
            <input class="custom-select mb-2 mr-sm-2 mb-sm-0" placeholder="Name">
            <button class="btn btn-success   mr-1 " style="float:right"><i class="fa fa-filter" aria-hidden="true"></i> Filter</button>
            <button class="btn btn-info  text-right"><i class="fa fa-search" aria-hidden="true"></i> Search</button>
          </form>
          <table class="table table-bordered table-striped table-responsive table-sm mt-5">
            <thead>
              <tr>
                <th class="align-middle text-center" colspan="3">Customer</th>
                <th class="align-middle text-center" rowspan="2">Address</th>
                <th class="align-middle text-center" rowspan="2">Contact Person</th>
                <th class="align-middle text-center" rowspan="2">Business Name</th>
                <th class="align-middle text-center" rowspan="2">Customer Type</th>
                <th class="align-middle text-center" rowspan="2">Area</th>
                <th class="align-middle text-center" rowspan="2">A/R Account</th>
                <th class="align-middle text-center" rowspan="2">Credit Limit</th>
                <th class="align-middle text-center" rowspan="2">Percentage Tax</th>
                <th class="align-middle text-center" rowspan="2">Customer Classification</th>
              </tr>
              <tr>
                <th class="align-middle text-center">Code</th>
                <th class="align-middle text-center">Name</th>
                <th class="align-middle text-center">Owner</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>16 Fried and Grill</td>
                <td>168 Fried chicken</td>
                <td>Charlotte Go</td>
                <td>883 A.S. Fortuna St. Banilad</td>
                <td>Johnny Depp</td>
                <td>16 Fried and Grill</td>
                <td>Egg. Cust</td>
                <td>CEBU</td>
                <td>500</td>
                <td>10</td>
                <td>12%</td>
                <td>Regular</td>
              </tr>
              <tr>
                <td>16 Fried and Grill</td>
                <td>168 Fried chicken</td>
                <td>Charlotte Go</td>
                <td>883 A.S. Fortuna St. Banilad</td>
                <td>Johnny Depp</td>
                <td>16 Fried and Grill</td>
                <td>Egg. Cust</td>
                <td>CEBU</td>
                <td>500</td>
                <td>10</td>
                <td>12%</td>
                <td>Regular</td>
              </tr>
              <tr>
                <td>16 Fried and Grill</td>
                <td>168 Fried chicken</td>
                <td>Charlotte Go</td>
                <td>883 A.S. Fortuna St. Banilad</td>
                <td>Johnny Depp</td>
                <td>16 Fried and Grill</td>
                <td>Egg. Cust</td>
                <td>CEBU</td>
                <td>500</td>
                <td>10</td>
                <td>12%</td>
                <td>Regular</td>
              </tr>
              <tr>
                <td>16 Fried and Grill</td>
                <td>168 Fried chicken</td>
                <td>Charlotte Go</td>
                <td>883 A.S. Fortuna St. Banilad</td>
                <td>Johnny Depp</td>
                <td>16 Fried and Grill</td>
                <td>Egg. Cust</td>
                <td>CEBU</td>
                <td>500</td>
                <td>10</td>
                <td>12%</td>
                <td>Regular</td>
              </tr>
              <tr>
                <td>16 Fried and Grill</td>
                <td>168 Fried chicken</td>
                <td>Charlotte Go</td>
                <td>883 A.S. Fortuna St. Banilad</td>
                <td>Johnny Depp</td>
                <td>16 Fried and Grill</td>
                <td>Egg. Cust</td>
                <td>CEBU</td>
                <td>500</td>
                <td>10</td>
                <td>12%</td>
                <td>Regular</td>
              </tr>
              <tr>
                <td>16 Fried and Grill</td>
                <td>168 Fried chicken</td>
                <td>Charlotte Go</td>
                <td>883 A.S. Fortuna St. Banilad</td>
                <td>Johnny Depp</td>
                <td>16 Fried and Grill</td>
                <td>Egg. Cust</td>
                <td>CEBU</td>
                <td>500</td>
                <td>10</td>
                <td>12%</td>
                <td>Regular</td>
              </tr>
            </tbody>
          </table>

        </div>
      </div>
      <div class="col-sm-4" style="display:none">

      </div>
    </div>
    <modal :modal_size="'modal-xl'" ref="">
      <div slot="header">
          Customer Detail
      </div>
      <div slot="body">
        <div class="row mb-3">
          <div class="col-sm-12 text-right">
            <button class="btn btn-sm btn-outline-primary mr-3"><i class="fa fa-print" aria-hidden="true"></i> Print</button>
            <button class="btn btn-sm btn-secondary"><i class="fa fa-chevron-left" aria-hidden="true"></i> Previous</button>
            <button class="btn btn-sm btn-secondary"><i class="fa fa-chevron-right" aria-hidden="true"></i> Next</button>
            <button class="btn btn-sm btn-secondary"><i class="fa fa-refresh" aria-hidden="true"></i> Refresh</button>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-6">
            <div class="card ">
              <h6 class="card-header card-primary text-white">General</h6>
              <div class="card-block">
                <form>
                  <div class="form-group row">
                    <label f class="col-sm-4 col-form-label">Customer ID</label>
                    <div class="col-sm-8">
                      <input type="text" class="form-control" id="inputEmail3" placeholder="Customer ID">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label f class="col-sm-4 col-form-label">Business Name</label>
                    <div class="col-sm-8">
                      <input type="text" class="form-control" id="inputEmail3" placeholder="Business Name">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label f class="col-sm-4 col-form-label">Owner</label>
                    <div class="col-sm-8">
                      <input type="text" class="form-control" id="inputEmail3" placeholder="Address">
                    </div>
                  </div>
                  <hr>
                  <div class="form-group row">
                    <label f class="col-sm-4 col-form-label">Customer Type</label>
                    <div class="col-sm-8">
                      <input type="text" class="form-control" id="inputEmail3" placeholder="Customer Type">
                    </div>
                  </div>
                  <hr>
                  <div class="form-group row">
                    <label f class="col-sm-4 col-form-label">A/R Account</label>
                    <div class="col-sm-8">
                      <select type="text" class="form-control" id="inputEmail3" placeholder="Area">
                        <option>Select</option>
                      </select>
                    </div>
                  </div>


                  <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Credit Limit</label>
                    <div class="col-sm-8 ">
                      <input type="text" class="form-control" id="inputEmail3" placeholder="Credit Limit">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label f class="col-sm-4 col-form-label">Credit Term(Days)</label>
                    <div class="col-sm-8">
                      <input type="text" class="form-control" id="inputEmail3" placeholder="Credit Term">
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="card ">
              <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs">
                  <li class="nav-item">
                    <a class="nav-link active" href="#">Others</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="#">History</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="#">Price List</a>
                  </li>
                </ul>
              </div>
              <div class="card-block">
                <div class="form-group row">
                  <label class="col-sm-4 col-form-label">Billing Address</label>
                  <div class="col-sm-8 ">
                    <input type="text" class="form-control" id="inputEmail3" placeholder="Billing Address">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-sm-4 col-form-label">Shipping Address</label>
                  <div class="col-sm-8 ">
                    <input type="text" class="form-control" id="inputEmail3" placeholder="Shipping Address">
                  </div>
                </div>
                <hr>
                <div class="form-group row">
                  <label class="col-sm-4 col-form-label">Contact Person</label>
                  <div class="col-sm-8 ">
                    <input type="text" class="form-control" id="inputEmail3" placeholder="Contact Person">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-sm-4 col-form-label">Contact Number</label>
                  <div class="col-sm-8 ">
                    <input type="text" class="form-control" id="inputEmail3" placeholder="Contact Number">
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>
        <div class="row">
          <div class="col-sm-12 text-right">
            <small>Modified by John Doe on August 5, 2017 10:20 am</small>
          </div>
        </div>
        <div class="row mt-3">
          <div class="col-sm-6">
            <button class="btn btn-primary"><i class="fa fa-plus-square" aria-hidden="true"></i> Add More</button>
          </div>
          <div class="col-sm-6 text-right">
            <button class="btn btn-success"><i class="fa fa-save" aria-hidden="true"></i> Save</button>
            <button class="btn btn-secondary">Close</button>
          </div>
        </div>
      </div>
    </modal>
    <modal :modal_size="'modal-xl'" ref="sampleModal">
      <div slot="header">
          Customer Details
      </div>
      <div slot="body">
        <div class="row mb-3">
          <div class="col-sm-12 text-right">
            <button class="btn btn-sm btn-outline-primary mr-3"><i class="fa fa-print" aria-hidden="true"></i> Print</button>
            <button class="btn btn-sm btn-secondary"><i class="fa fa-chevron-left" aria-hidden="true"></i> Previous</button>
            <button class="btn btn-sm btn-secondary"><i class="fa fa-chevron-right" aria-hidden="true"></i> Next</button>
            <button class="btn btn-sm btn-secondary"><i class="fa fa-refresh" aria-hidden="true"></i> Refresh</button>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-6">
            <div class="card ">
              <h6 class="card-header card-primary text-white">General</h6>
              <div class="card-block">
                <form>
                  <div class="form-group row">
                    <label f class="col-sm-4 col-form-label">Customer ID</label>
                    <div class="col-sm-8">
                      <input type="text" class="form-control" id="inputEmail3" placeholder="Customer ID">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label f class="col-sm-4 col-form-label">Business Name</label>
                    <div class="col-sm-8">
                      <input type="text" class="form-control" id="inputEmail3" placeholder="Business Name">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label f class="col-sm-4 col-form-label">Owner</label>
                    <div class="col-sm-8">
                      <input type="text" class="form-control" id="inputEmail3" placeholder="Address">
                    </div>
                  </div>
                  <hr>
                  <div class="form-group row">
                    <label f class="col-sm-4 col-form-label">Customer Type</label>
                    <div class="col-sm-8">
                      <input type="text" class="form-control" id="inputEmail3" placeholder="Customer Type">
                    </div>
                  </div>
                  <hr>
                  <div class="form-group row">
                    <label f class="col-sm-4 col-form-label">A/R Account</label>
                    <div class="col-sm-8">
                      <select type="text" class="form-control" id="inputEmail3" placeholder="Area">
                        <option>Select</option>
                      </select>
                    </div>
                  </div>


                  <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Credit Limit</label>
                    <div class="col-sm-8 ">
                      <input type="text" class="form-control" id="inputEmail3" placeholder="Credit Limit">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label f class="col-sm-4 col-form-label">Credit Term(Days)</label>
                    <div class="col-sm-8">
                      <input type="text" class="form-control" id="inputEmail3" placeholder="Credit Term">
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="card ">
              <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs">
                  <li class="nav-item">
                    <a class="nav-link active" href="#">Others</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="#">History</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="#">Price List</a>
                  </li>
                </ul>
              </div>
              <div class="card-block">
                <div class="form-group row">
                  <label class="col-sm-4 col-form-label">Billing Address</label>
                  <div class="col-sm-8 ">
                    <input type="text" class="form-control" id="inputEmail3" placeholder="Billing Address">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-sm-4 col-form-label">Shipping Address</label>
                  <div class="col-sm-8 ">
                    <input type="text" class="form-control" id="inputEmail3" placeholder="Shipping Address">
                  </div>
                </div>
                <hr>
                <div class="form-group row">
                  <label class="col-sm-4 col-form-label">Contact Person</label>
                  <div class="col-sm-8 ">
                    <input type="text" class="form-control" id="inputEmail3" placeholder="Contact Person">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-sm-4 col-form-label">Contact Number</label>
                  <div class="col-sm-8 ">
                    <input type="text" class="form-control" id="inputEmail3" placeholder="Contact Number">
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>
        <div class="row">
          <div class="col-sm-12 text-right">
            <small>Modified by John Doe on August 5, 2017 10:20 am</small>
          </div>
        </div>
        <div class="row mt-3">
          <div class="col-sm-6">
            <button class="btn btn-primary"><i class="fa fa-plus-square" aria-hidden="true"></i> Add More</button>
          </div>
          <div class="col-sm-6 text-right">
            <button class="btn btn-success"><i class="fa fa-save" aria-hidden="true"></i> Save</button>
            <button class="btn btn-secondary">Close</button>
          </div>
        </div>
      </div>
    </modal>
  </div>
</template>
<script>
  export default{
    name: '',
    create(){

    },
    components: {
      'modal': require('../../components/modal/Modal.vue')
    },
    mounted(){
      this.$refs.sampleModal.showModal()
    },
    data(){
      return {

      }
    },
    props: {
    },
    methods: {
    }

  }
</script>
<style scoped>

</style>
