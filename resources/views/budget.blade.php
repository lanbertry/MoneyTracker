@extends('layout')
@section('title', "Budget")
@section('content')

<div class="main-container d-flex">
    @include('include/sidebar')
    <div class="content">


        <div class="dashboard-content pt-2">
            <h2 class="ps-5 pb-1 text-budget">Budget</h2>
            <div class="container-fluid outside">
                <div class="box">
                    <div class="align">
                        <div class="container">
                            <h2 class="pt-3 text-budget-plan">Budget Plan</h2>
                            <hr class="hr-line">
                            <h2 class="text-budget-plan">Input budget for a specific Date:</h2>
                        </div>
                        <div class="container">
                            <form action="{{ route('budget.add') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col">
                                        <label for="budget_type" class="labelColor pb-1">Budget type:</label><br>
                                        <input type="text"
                                            class="form-control inputSize @error('budget_type') is-invalid @enderror"
                                            name="budget_type">
                                        {!! $errors->first('budget_type', '<span class="invalid-feedback"
                                            role="alert">:message</span>') !!} <br>
                                        <label for="budget_type" class="labelColor pb-1">Category:</label><br>
                                        <select id="category"
                                            class="form-control inputCategory @error('category') is-invalid @enderror"
                                            name="category">
                                            <option value="Select">--Select--</option>
                                            <option value="Education">Education</option>
                                            <option value="Entertainment">Entertainment</option>
                                            <option value="Food">Food</option>
                                            <option value="Health">Health</option>
                                            <option value="Miscellaneous">Miscellaneous</option>
                                            <option value="Shopping">Shopping</option>
                                            <option value="Transportation">Transportation</option>
                                            <option value="Utilities">Utilities</option>
                                        </select>
                                        {!! $errors->first('category', '<span class="invalid-feedback"
                                            role="alert">:message</span>') !!}
                                    </div>
                                    <div class="col-lg-2"></div>
                                    <div class="col col2-budget">
                                        <label for="budget_type" class="labelColor pb-1">Amount:</label><br>
                                        <div class="input-group amount">
                                            <div class="input-group-append peso">
                                                <span class="input-group-text">&#8369;</span>
                                            </div>
                                            <input type="text"
                                                class="form-control @error('amount') is-invalid @enderror"
                                                name="amount" />
                                            {!! $errors->first('amount', '<span class="invalid-feedback"
                                                role="alert">:message</span>') !!}
                                        </div>
                                        <label for="budget_type" class="labelColor pt-4 pb-1">Date:</label><br>
                                        <div class="input-group date" id="picker">
                                            <input type="text" class="form-control @error('date') is-invalid @enderror"
                                                readonly name="date" />
                                            <div class="input-group-append appdate">
                                                <span class="input-group-text textdate">
                                                    <i class="fas fa-calendar"></i>
                                                </span>
                                            </div>
                                        </div>


                                    </div>
                                </div>

                                <div class="row justify-content-end pt-2">
                                    <button type="submit" class="add">Add</button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid outside pt-3">
            <div class="boxbelow">
                <div class="align">
                    <div class="box2 text-center pt-3">
                        <div class="row row1 pt-4">
                            <div class="col colum1">
                                <h4>Budget Type</h4>
                            </div>
                            <div class="col colum2">
                                <h4 class="grey">Category</h4>
                            </div>
                            <div class="col colum3">
                                <h4 class="amountalign">Amount</h4>
                            </div>
                            <div class="col colum4">
                                <h4 class="grey datealign">Date</h4>
                            </div>
                        </div>
                        <!-- container of loaded budgets of user -->
                        <div class="container scrollbar">

                            <div class="pt-5 d-flex justify-content-center align-items-center">
                                <div class="spinner-border" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <b class="ms-2">Loading Data...</b>
                            </div>

                        </div>


                    </div>
                    <div class="row rowbotborder"></div>
                </div>
            </div>
        </div>

    </div>
</div>


<script>

    // Call the function to load user budgets
    loadUserBudgets();

    function deleteBudget(button) {
        const budgetId = button.dataset.budgetId;

        fetch(`http://moneytracker.test/budgetdelete/${budgetId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log(data.message);
                loadUserBudgets();
            })
            .catch(error => {
                console.error('There was a problem with the fetch operation:', error);
            });
    }

    function loadUserBudgets() {
        fetch('http://moneytracker.test/get-user-budgets', {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                // Clear usa ang sulod na content
                document.querySelector('.scrollbar').innerHTML = '';

                // E load ang each data ni user sa database
                data.userBudgets.forEach(budget => {
                    const row = document.createElement('div');
                    row.classList.add('row', 'row1', 'pt-4');
                    row.innerHTML = `

                    <div class="col colum1">
                        <h4>${ucfirst(budget.budget_type)}</h4>
                    </div>
                    <div class="col colum2">
                        <h4 class="grey">${budget.category}</h4>
                    </div>
                    <div class="col colum3">
                        <h4 class="pl-5">&#8369;${budget.amount}</h4>
                    </div>
                    <div class="col colum4 d-flex">
                        <h4 class="grey">${moment(budget.date).format('DD-MM-YYYY')}</h4>
                        <div class="dropdown pt-1">
                        <button style="color: #80AC64; border:1px solid #80AC64; background: #F1F1F1;"
                            class="btn dropdown-toggle butdrop" type="button" id="dropdownMenu2"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                        <div style="background:#ECFAE2;border: 1px solid black;border-radius: 10px;"
                            class="dropdown-menu" aria-labelledby="dropdownMenu2">
                            <button style="border-bottom: 1px solid black; text-align: center;"
                                class="dropdown-item" type="button">Edit
                            </button>
                            <button style="text-align: center;" class="dropdown-item" name="delete" data-budget-id="${budget.budget_id}" onclick="deleteBudget(this)">Delete</button>

                        </div>
                    </div>
                    </div>


                `;

                    document.querySelector('.scrollbar').appendChild(row);
                });
            })
            .catch(error => {
                console.error('There was a problem with the fetch operation:', error);
            });
    }

    // Helper function to capitalize the first letter
    function ucfirst(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }


</script>




<style>
    /* Style the calendar icon */
    .textdate {
        background-color: #ffffff;
        cursor: pointer;
        height: 38px;
    }

    .add {
        background: #8CEF84;
        border-radius: 8px;
        border: none;
    }

    .amountalign {
        padding-right: 30px;
    }

    .datealign {
        padding-right: 110px;
    }

    .dropdown {
        padding-left: 20px;
        justify-content: center;

    }

    .scrollbar {
        height: 220px;
        max-height: 220px;
        overflow-y: scroll;
    }

    .grey {
        color: #757575;
    }

    .invis {
        color: #F8FFF3;
        background: #F8FFF3;
    }

    .add-scrollbar {
        height: 220px;
        max-height: 220px;
        overflow: hidden;
    }

    .rowbotborder {
        border-top: 1px solid grey;
    }

    .box2 .row {
        border-bottom: 1px solid grey;
    }

    .boxbelow {
        width: 1100px;
        height: 330px;
        background: #F8FFF3;
        border: 1px solid #757575;
        box-shadow: 0 2px 1px rgba(117, 117, 117, 0.3);
        border-radius: 10px;
    }

    .grey {
        color: #757575;
    }


    body {
        font-family: 'inter';

    }

    .text-budget {
        font-size: 40;
        font-weight: bold;
    }

    .text-budget-plan {
        font-size: 25;
        font-weight: bold;
    }

    .outside {
        display: flex;
        align-items: center;
        justify-content: center;

    }

    .box {
        width: 1100px;
        background: #F8FFF3;
        border: 1px solid #757575;
        box-shadow: 0 2px 1px rgba(117, 117, 117, 0.3);
        border-radius: 10px;
    }

    .align {
        width: 900px;
        margin: auto;
    }

    .labelColor {
        color: #757575;
    }



    .input-group-text i.fas.fa-calendar:hover {
        cursor: pointer;
    }

    @media (max-width: 768px) {

        .box,
        .align {
            max-width: 90%;
        }
    }
</style>
