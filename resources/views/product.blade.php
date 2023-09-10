<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validation with validate.js and Vue.js</title>
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/validate.js/0.13.1/validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <!-- Add these links in the <head> section of your HTML document -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px 50px 50px 30px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }

        h1 {
            text-align: center;
        }

        form {
            text-align: center;
        }

        input[type="text"],
        input[type="email"],
        input[type="file"],
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .error{
            color: red;
        }
    </style>

</head>
<body>

<div id="app" class="container">
    <h1>Contact Us</h1>


{{--
    <input type="file" @change="handleFileUpload" accept=".csv" />

    <table>
      
    </table>
--}}



    
    <form @submit.prevent="submitForm"  enctype="multipart/form-data">
    <div class="row">
        <div class="col-xs-12">
            <label for="name">Name:</label>
            <input type="text" v-model="formData.name">
            <span v-if="errors && errors.name" class="error">
                <span v-html="errors.name[0]"></span>
            </span>
        </div>
        <div class="col-xs-12">
            <label for="csvFile">Upload a CSV File:</label>
            <input type="file" id="csvFile" name="csvFile" @change="handleFileUpload">
            <span v-if="errors && errors.csvFile" class="error">
                 <span v-html="errors.csvFile[0]"></span>
            </span>
        </div>
        <div class="col-xs-12">
            <input type="submit" value="Submit">
        </div>
    </div>
    </form>
    
</div>


<!-- The modal -->
<div class="modal fade" id="csvModal" tabindex="-1" role="dialog" aria-labelledby="csvModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" >
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="csvModalLabel">CSV Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered" id="csvTable" >
                    <thead>
        <tr>
          <th>Column 1</th>
          <th>Column 2</th>
          <!-- Add more columns as needed -->
        </tr>
      </thead>
      <tbody>
        <tr v-for="(row, index) in csvData" :key="index">
          <td v-for="(value, colIndex) in row" :key="colIndex" v-html="value"></td>
        </tr>
      </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<script>
    // Define a new Vue instance
    const app = new Vue({
        el: '#app',
        data: {
            formData: {
                name: '',
                csvFile: null,
            },
            errors: {},
            csvData: [], 
            dataAry:[],
        },
        methods: {
            handleFileUpload(event) {
              const file = event.target.files[0];
              const reader = new FileReader();

              reader.onload = (e) => {
                const contents = e.target.result;
                this.parseCSV(contents);
              };

              reader.readAsText(file);
            },
            parseCSV(contents) {
              // Split the CSV content into rows and columns
              const rows = contents.split('\n');
              const data = [];

              for (let i = 0; i < rows.length; i++) {
                const columns = rows[i].split(',');
                data.push(columns);
              }

              this.csvData = data;
            },


            handleFileChangeOld(event) {
                this.formData.csvFile = event.target.files[0];
            },
            openCSVModal() {
                if (this.formData.csvFile) {
                    // Parse the CSV file and store the data in the csvData property
                    this.csvData = this.parseCSVFile(this.formData.csvFile);
                    console.log(this.csvData[0]);
                    // Show the modal
                } else {
                    alert('Please select a CSV file.');
                }
            },            
            parseCSVFile(file) {
                // Function to parse the CSV file and return data as an array
                const reader = new FileReader();
                const lines = [];
                var myary=  this.dataAry;
                reader.onload = (event) => {
                    const csvText = event.target.result;
                    const rows = csvText.split('\n');

                    for (let i = 0; i < rows.length; i++) {
                        const columns = rows[i].split(',');
                        
                        lines.push(columns);
                    }
                };
                reader.readAsText(file);

                return lines;
            },
            submitForm() {
                // Define validation constraints
                const constraints = {
                    // name: {
                    //     presence: { allowEmpty: false },
                    // },
                    csvFile: {
                        presence: { allowEmpty: false },
                        
                    },
                };

                // Validate the form data
                const validationErrors = validate(this.formData, constraints);

                // Reset previous errors
                this.errors = {};

                // If there are validation errors, display them
                if (validationErrors) {
                    this.errors = validationErrors;
                } else {


                    $('#csvModal').modal('show');
                    
                    this.openCSVModal();
                    return;
                    // If no errors, submit the form via AJAX using Axios
                    axios.post("{{url('importdata')}}", this.formData)
                        .then(response => {

                            // Handle the response from the server here
                            alert(response.data.message);
                        })
                        .catch(error => {
                            // Handle any errors that occur during the AJAX request
                            console.error(error);
                        });
                }
            }
        }
    });
</script>
</body>
</html>