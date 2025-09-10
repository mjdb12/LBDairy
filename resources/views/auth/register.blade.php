<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>LBDAIRY: User Registration</title>

    <!-- Custom fonts for this template-->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --primary-color: #18375d;
            --primary-dark: #122a47;
            --success-color: #1cc88a;
            --success-dark: #17a673;
            --warning-color: #f6c23e;
            --danger-color: #e74a3b;
            --info-color: #36b9cc;
            --light-color: #f8f9fc;
            --dark-color: #5a5c69;
            --border-color: #e3e6f0;
            --shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            --shadow-lg: 0 1rem 3rem rgba(0, 0, 0, 0.175);
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #fff;
            min-height: 100vh;
            padding: 2rem 0;
        }

        .card {
            border: none;
            border-radius: 20px;
            box-shadow: var(--shadow-lg);
            transition: all 0.3s ease;
            overflow: hidden;
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.98);
            margin: 1rem auto;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 2rem 4rem rgba(0, 0, 0, 0.2);
        }

        .logo-container {
            position: relative;
            margin-bottom: 2.2 rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
        }

        .logo-container img {
            object-fit: contain;
            filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.1));
            transition: transform 0.3s ease;
        }

        .logo-container img:hover {
            transform: scale(1.05);
        }

        .form-control-user {
            border: 2px solid #e3e6f0;
            border-radius: 15px;
            padding: 1.25rem 1.5rem;
            font-size: 1rem;
            font-weight: 400;
            background: white;
            transition: all 0.3s ease;
            line-height: 1.5;
        }

        /* Input fields get the dark color */
        input.form-control-user {
            color: var(--dark-color);
        }

        .form-control-user:focus {
            border-color: var(--primary-color);
            background: white;
            box-shadow: 0 0 0 0.2rem rgba(24, 55, 93, 0.25);
            transform: translateY(-2px);
        }

        .btn-user {
             background: #18375d;
            border-radius: 15px;
            padding: 1.25rem 1.5rem;
            font-size: 1.1rem;
            font-weight: 600;
            text-transform: none;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .btn-user:hover {
            background-color: #fca700;
            color: #ffffffff;
            border-color: #fca700;
            transform: translateY(-2px);
            transform: translateY(-3px);
            box-shadow: 0 1rem 2rem rgba(24, 55, 93, 0.4);
        }

        .nav-tabs {
            border: none;
            margin-bottom: 2rem;
        }

        .nav-tabs .nav-link {
            border: none;
            border-radius: 15px;
            margin-right: 1rem;
            padding: 1rem 2rem;
            font-weight: 600;
            color: var(--dark-color);
            background: var(--light-color);
            transition: all 0.3s ease;
        }

        .nav-tabs .nav-link:hover {
            background: rgba(24, 55, 93, 0.1);
            color: var(--primary-color);
            transform: translateY(-2px);
        }

        .nav-tabs .nav-link.active {
            background: var(--primary-color);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(24, 55, 93, 0.3);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 0.75rem;
            font-size: 0.95rem;
        }

        .custom-control {
            position: relative;
            display: block;
            min-height: 1.5rem;
            padding-left: 1.5rem;
        }

        .custom-control-input {
            position: absolute;
            left: 0;
            z-index: -1;
            width: 1rem;
            height: 1.25rem;
            opacity: 0;
        }

        .custom-control-label {
            position: relative;
            margin-bottom: 0;
            vertical-align: top;
            cursor: pointer;
            font-size: 1rem;
            color: var(--dark-color);
            font-weight: 500;
            line-height: 1.5;
        }

        .custom-control-label::before {
            position: absolute;
            top: 0.25rem;
            left: -1.5rem;
            display: block;
            width: 1rem;
            height: 1rem;
            pointer-events: none;
            content: "";
            background-color: #fff;
            border: 2px solid var(--border-color);
            border-radius: 0.25rem;
            transition: all 0.3s ease;
        }

        .custom-control-label::after {
            position: absolute;
            top: 0.25rem;
            left: -1.5rem;
            display: block;
            width: 1rem;
            height: 1rem;
            content: "";
            background-repeat: no-repeat;
            background-position: center;
            background-size: 0.5rem 0.5rem;
            transition: all 0.3s ease;
        }

        .custom-control-input:checked ~ .custom-control-label::before {
            color: #fff;
            border-color: var(--primary-color);
            background-color: var(--primary-color);
        }

        .custom-control-input:checked ~ .custom-control-label::after {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%23fff' d='M6.564.75l-3.59 3.612-1.538-1.55L0 4.26 2.974 7.25 8 2.193z'/%3e%3c/svg%3e");
        }

        .custom-control-input:focus ~ .custom-control-label::before {
            box-shadow: 0 0 0 0.2rem rgba(24, 55, 93, 0.25);
        }

        .custom-control-input:hover ~ .custom-control-label::before {
            border-color: var(--primary-color);
        }

        .modal-content {
            border: none;
            border-radius: 20px;
            box-shadow: var(--shadow-lg);
            backdrop-filter: blur(10px);
        }

        .modal-header {
            background: var(--primary-color);
            color: white;
            border-bottom: none;
            border-radius: 20px 20px 0 0;
            padding: 2rem;
        }

        .modal-title {
            font-weight: 700;
            font-size: 1.4rem;
        }

        .modal-body {
            padding: 2.5rem;
            font-size: 1rem;
            line-height: 1.6;
        }

        .modal-footer {
            border-top: 1px solid var(--border-color);
            padding: 1.5rem 2.5rem;
        }

        .small {
            color: var(--primary-color);
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 1rem;
        }

        .small:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        .terms-link {
            color: var(--primary-color) !important;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .terms-link:hover {
            color: var(--primary-dark) !important;
            text-decoration: underline;
        }

        .is-invalid {
            border-color: var(--danger-color) !important;
            box-shadow: 0 0 0 0.2rem rgba(231, 74, 59, 0.25) !important;
        }

        .registration-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #18375d;
            margin-bottom: 0.5rem;
        }

        .registration-subtitle {
            font-size: 1.1rem;
            color: #6b7280;
            margin-bottom: 2rem;
        }

        .alert {
            border: none;
            border-radius: 15px;
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
        }

        .alert-danger {
            background: rgba(231, 74, 59, 0.1);
            color: var(--danger-color);
            border-left: 4px solid var(--danger-color);
        }

        .alert-success {
            background: rgba(28, 200, 138, 0.1);
            color: var(--success-color);
            border-left: 4px solid var(--success-color);
        }

         .tab-active {
            background: #387057;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(24, 55, 93, 0.3);
        }
        .tab-inactive {
            background: rgba(255, 255, 255, 0.1);
            color: #6b7280;
            backdrop-filter: blur(8px);
        }

        @media (max-width: 768px) {
            .card {
                margin: 1rem;
                border-radius: 15px;
            }

            .registration-title {
                font-size: 2rem;
            }

            .form-control-user {
                padding: 1rem 1.25rem;
                font-size: 0.95rem;
            }

            .btn-user {
                padding: 1rem 1.5rem;
                font-size: 1rem;
            }

            .nav-tabs .nav-link {
                padding: 0.75rem 1.5rem;
                margin-right: 0.5rem;
                margin-bottom: 0.5rem;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <div class="logo-container">
                                <img src="{{ asset('img/LBDairy.png') }}" alt="LBDAIRY Logo" style="width: 150px; height: 150px;">
                            </div>
                            <h1 class="registration-title" >Join LBDAIRY</h1>
                            <p class="registration-subtitle">Create your account and start managing your dairy operations</p>
                        </div>

                        <!-- User Type Tabs -->
                                <div class="rounded-2xl p-2 bg-gray-100 max-w-md mx-auto h-16">
                                    <ul class="nav nav-tabs justify-content-center w-full" id="registrationTabs" role="tablist">
                                        <li class="flex-1" role="presentation">
                                            <button class="w-full tab-btn py-3 px-4 rounded-xl text-sm font-medium transition-all duration-300 tab-active" 
                                                    id="farmer-tab" data-toggle="tab" data-target="#farmer" type="button" role="tab" 
                                                    aria-controls="farmer" aria-selected="true">
                                                <div class="flex items-center justify-center space-x-2">
                                                    <span>Farmer</span>
                                                </div>
                                            </button>
                                        </li>
                                        <li class="flex-1" role="presentation">
                                            <button class="w-full tab-btn py-3 px-4 rounded-xl text-sm font-medium transition-all duration-300 tab-inactive" 
                                                    id="admin-tab" data-toggle="tab" data-target="#admin" type="button" role="tab" 
                                                    aria-controls="admin" aria-selected="false">
                                                <div class="flex items-center justify-center space-x-2">
                                                    <span>Admin</span>
                                                </div>
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                        <!-- User Type Tabs -->
                        <br>
                        <!-- Tab Content -->
                        <div class="tab-content" id="registrationTabContent">
                            <!-- Farmer Registration Form -->
                            <div class="tab-pane fade show active" id="farmer" role="tabpanel" aria-labelledby="farmer-tab">
                                <form method="POST" action="{{ route('register') }}" class="user" id="farmerForm">
                                    @csrf
                                    <input type="hidden" name="role" value="farmer">
                                    
                                    @if ($errors->any())
                                        <div class="alert alert-danger">
                                            <ul class="mb-0">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    <div class="form-group">
                                        <label for="farmerCode" class="block text-sm font-medium text-gray-700 mb-2">Farmer Registration Code</label>
                                        <input type="text" id="farmerCode" name="farmer_code" required
                                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent input-focus transition-all duration-300"
                                            placeholder="Enter your farmer code">
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-6 mb-3 mb-sm-0">
                                            <label for="farmerFirstName" class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                                            <input type="text"  id="farmerFirstName" name="first_name" required
                                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent input-focus transition-all duration-300"
                                            placeholder="Enter your first name">
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="farmerLastName" class="block text-sm font-medium text-gray-700 mb-2">Last Name</label>
                                            <input type="text" id="farmerLastName" name="last_name" required
                                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent input-focus transition-all duration-300"
                                            placeholder="Enter your last name">
                                        </div>
                                    </div>

                                        <div class="form-group">
                                         <label for="farmerBarangay" class="block text-sm font-medium text-gray-700 mb-2">Barangay</label>
                                         <input list="farmerBarangayList"  id="farmerBarangay" name="barangay" placeholder="Select Barangay" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent input-focus transition-all duration-300"
                                            placeholder="Enter your barangay">
                                         <datalist id="farmerBarangayList">
                                             <option value="Abang">Abang</option>
                                             <option value="Aliliw">Aliliw</option>
                                             <option value="Atulinao">Atulinao</option>
                                             <option value="Ayuti (Poblacion)">Ayuti (Poblacion)</option>
                                             <option value="Barangay 1 (Poblacion)">Barangay 1 (Poblacion)</option>
                                             <option value="Barangay 2 (Poblacion)">Barangay 2 (Poblacion)</option>
                                             <option value="Barangay 3 (Poblacion)">Barangay 3 (Poblacion)</option>
                                             <option value="Barangay 4 (Poblacion)">Barangay 4 (Poblacion)</option>
                                             <option value="Barangay 5 (Poblacion)">Barangay 5 (Poblacion)</option>
                                             <option value="Barangay 6 (Poblacion)">Barangay 6 (Poblacion)</option>
                                             <option value="Barangay 7 (Poblacion)">Barangay 7 (Poblacion)</option>
                                             <option value="Barangay 8 (Poblacion)">Barangay 8 (Poblacion)</option>
                                             <option value="Barangay 9 (Poblacion)">Barangay 9 (Poblacion)</option>
                                             <option value="Barangay 10 (Poblacion)">Barangay 10 (Poblacion)</option>
                                             <option value="Igang">Igang</option>
                                             <option value="Kabatete">Kabatete</option>
                                             <option value="Kakawit">Kakawit</option>
                                             <option value="Kalangay">Kalangay</option>
                                             <option value="Kalyaat">Kalyaat</option>
                                             <option value="Kilib">Kilib</option>
                                             <option value="Kulapi">Kulapi</option>
                                             <option value="Mahabang Parang">Mahabang Parang</option>
                                             <option value="Malupak">Malupak</option>
                                             <option value="Manasa">Manasa</option>
                                             <option value="May-It">May-It</option>
                                             <option value="Nagsinamo">Nagsinamo</option>
                                             <option value="Nalunao">Nalunao</option>
                                             <option value="Palola">Palola</option>
                                             <option value="Piis">Piis</option>
                                             <option value="Samil">Samil</option>
                                             <option value="Tiawe">Tiawe</option>
                                             <option value="Tinamnan">Tinamnan</option>
                                         </datalist>
                                     </div>

                                    <div class="form-group row">
                                        <div class="col-sm-6 mb-3 mb-sm-0">
                                            <label for="farmerContactNumber" class="block text-sm font-medium text-gray-700 mb-2">Contact Number</label>
                                            <input type="tel" id="farmerContactNumber" name="phone" pattern="[0-9]{11}" maxlength="11" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent input-focus transition-all duration-300"
                                                placeholder="Enter your contact number">
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="farmerEmail" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                                            <input type="email" id="farmerEmail" name="email" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent input-focus transition-all duration-300"
                                                placeholder="Enter your email address">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                            <label for="farmerFarmAddress">Farm Address</label>
                                            <input list="farmAddressList" id="farmerFarmAddress" name="farm_address" required class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent input-focus transition-all duration-300"
                                                placeholder="Select address">
                                            <datalist id="farmAddressList">
                                             <option value="Abang">Abang</option>
                                             <option value="Aliliw">Aliliw</option>
                                             <option value="Atulinao">Atulinao</option>
                                             <option value="Ayuti (Poblacion)">Ayuti (Poblacion)</option>
                                             <option value="Barangay 1 (Poblacion)">Barangay 1 (Poblacion)</option>
                                             <option value="Barangay 2 (Poblacion)">Barangay 2 (Poblacion)</option>
                                             <option value="Barangay 3 (Poblacion)">Barangay 3 (Poblacion)</option>
                                             <option value="Barangay 4 (Poblacion)">Barangay 4 (Poblacion)</option>
                                             <option value="Barangay 5 (Poblacion)">Barangay 5 (Poblacion)</option>
                                             <option value="Barangay 6 (Poblacion)">Barangay 6 (Poblacion)</option>
                                             <option value="Barangay 7 (Poblacion)">Barangay 7 (Poblacion)</option>
                                             <option value="Barangay 8 (Poblacion)">Barangay 8 (Poblacion)</option>
                                             <option value="Barangay 9 (Poblacion)">Barangay 9 (Poblacion)</option>
                                             <option value="Barangay 10 (Poblacion)">Barangay 10 (Poblacion)</option>
                                             <option value="Igang">Igang</option>
                                             <option value="Kabatete">Kabatete</option>
                                             <option value="Kakawit">Kakawit</option>
                                             <option value="Kalangay">Kalangay</option>
                                             <option value="Kalyaat">Kalyaat</option>
                                             <option value="Kilib">Kilib</option>
                                             <option value="Kulapi">Kulapi</option>
                                             <option value="Mahabang Parang">Mahabang Parang</option>
                                             <option value="Malupak">Malupak</option>
                                             <option value="Manasa">Manasa</option>
                                             <option value="May-It">May-It</option>
                                             <option value="Nagsinamo">Nagsinamo</option>
                                             <option value="Nalunao">Nalunao</option>
                                             <option value="Palola">Palola</option>
                                             <option value="Piis">Piis</option>
                                             <option value="Samil">Samil</option>
                                             <option value="Tiawe">Tiawe</option>
                                             <option value="Tinamnan">Tinamnan</option>
                                         </datalist>
                                         <input type="hidden" name="address" id="farmerAddress">
                                        </div>

                                    <div class="form-group row">
                                        <div class="col-sm-6 mb-3 mb-sm-0">
                                            <label for="farmerFarmName" class="block text-sm font-medium text-gray-700 mb-2">Farm Name</label>
                                            <input type="text" id="farmerFarmName" name="farm_name" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent input-focus transition-all duration-300"
                                            placeholder="Enter your farm name">
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="farmerUsername" class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                                            <input type="text" id="farmerUsername" name="username" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent input-focus transition-all duration-300"
                                                placeholder="Enter your username">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-sm-6 mb-3 mb-sm-0">
                                            <label for="farmerPassword" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                                            <div class="relative">
                                            <input type="password" id="farmerPassword" name="password" required
                                                class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent input-focus transition-all duration-300"
                                                placeholder="Enter your password">
                                            <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center" onclick="togglePassword('farmerPassword')">
                                                <i class="fas fa-eye text-gray-400 hover:text-gray-600 transition-colors duration-200" id="farmerPasswordIcon"></i>
                                            </button>
                                        </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <label for="farmerConfirmPassword" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                                            <div class="relative">
                                            <input type="password" id="farmerConfirmPassword" name="password_confirmation" required
                                                class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent input-focus transition-all duration-300"
                                                placeholder="Confirm your password">
                                            <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center" onclick="togglePassword('farmerConfirmPassword')">
                                                <i class="fas fa-eye text-gray-400 hover:text-gray-600 transition-colors duration-200" id="farmerConfirmPasswordIcon"></i>
                                            </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="farmerTermsCheckbox" name="terms_accepted" required>
                                            <label class="custom-control-label" for="farmerTermsCheckbox">
                                                I agree to the <a href="#" onclick="showTerms(); return false;" class="terms-link">Terms and Conditions</a>
                                            </label>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-user btn-block text-white font-semibold py-3 px-6 rounded-xl text-lg transition-all duration-300">
                                        Register as Farmer
                                    </button>
                                </form>
                            </div>

                            <!-- Admin Registration Form -->
                            <div class="tab-pane fade" id="admin" role="tabpanel" aria-labelledby="admin-tab">
                                <form method="POST" action="{{ route('register') }}" class="user" id="adminForm">
                                    @csrf
                                    <input type="hidden" name="role" value="admin">
                                    
                                    @if ($errors->any())
                                        <div class="alert alert-danger">
                                            <ul class="mb-0">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    <div class="form-group">
                                        <label for="adminCode" class="block text-sm font-medium text-gray-700 mb-2">Admin Registration Code</label>
                                        <input type="text" id="adminCode" name="admin_code" required class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent input-focus transition-all duration-300"
                                                placeholder="Enter your admin code">
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-sm-6 mb-3 mb-sm-0">
                                            <label for="adminFirstName" class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                                            <input type="text" id="adminFirstName" name="first_name" required class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent input-focus transition-all duration-300"
                                                placeholder="Enter your first name">
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="adminLastName" class="block text-sm font-medium text-gray-700 mb-2">Last Name</label>
                                            <input type="text" id="adminLastName" name="last_name" required class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent input-focus transition-all duration-300"
                                                placeholder="Enter your last name">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="adminPosition" class="block text-sm font-medium text-gray-700 mb-2">Position</label>
                                        <input list="positionList" id="adminPosition" name="position" required class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent input-focus transition-all duration-300"
                                                placeholder="Select position">
                                        <datalist id="positionList">
                                            <option value="Municipal Agriculturist">Municipal Agriculturist</option>
                                            <option value="Livestock Technician">Livestock Technician</option>
                                            <option value="IT Staff">IT Staff</option>
                                        </datalist>
                                    </div>

                                     <div class="form-group">
                                         <label for="adminBarangay" class="block text-sm font-medium text-gray-700 mb-2">Barangay</label>
                                         <input list="adminBarangayList" id="adminBarangay" name="barangay" placeholder="Select Barangay" required class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent input-focus transition-all duration-300"
                                                placeholder="Select barangay">
                                         <datalist id="adminBarangayList">
                                             <option value="Abang">Abang</option>
                                             <option value="Aliliw">Aliliw</option>
                                             <option value="Atulinao">Atulinao</option>
                                             <option value="Ayuti (Poblacion)">Ayuti (Poblacion)</option>
                                             <option value="Barangay 1 (Poblacion)">Barangay 1 (Poblacion)</option>
                                             <option value="Barangay 2 (Poblacion)">Barangay 2 (Poblacion)</option>
                                             <option value="Barangay 3 (Poblacion)">Barangay 3 (Poblacion)</option>
                                             <option value="Barangay 4 (Poblacion)">Barangay 4 (Poblacion)</option>
                                             <option value="Barangay 5 (Poblacion)">Barangay 5 (Poblacion)</option>
                                             <option value="Barangay 6 (Poblacion)">Barangay 6 (Poblacion)</option>
                                             <option value="Barangay 7 (Poblacion)">Barangay 7 (Poblacion)</option>
                                             <option value="Barangay 8 (Poblacion)">Barangay 8 (Poblacion)</option>
                                             <option value="Barangay 9 (Poblacion)">Barangay 9 (Poblacion)</option>
                                             <option value="Barangay 10 (Poblacion)">Barangay 10 (Poblacion)</option>
                                             <option value="Igang">Igang</option>
                                             <option value="Kabatete">Kabatete</option>
                                             <option value="Kakawit">Kakawit</option>
                                             <option value="Kalangay">Kalangay</option>
                                             <option value="Kalyaat">Kalyaat</option>
                                             <option value="Kilib">Kilib</option>
                                             <option value="Kulapi">Kulapi</option>
                                             <option value="Mahabang Parang">Mahabang Parang</option>
                                             <option value="Malupak">Malupak</option>
                                             <option value="Manasa">Manasa</option>
                                             <option value="May-It">May-It</option>
                                             <option value="Nagsinamo">Nagsinamo</option>
                                             <option value="Nalunao">Nalunao</option>
                                             <option value="Palola">Palola</option>
                                             <option value="Piis">Piis</option>
                                             <option value="Samil">Samil</option>
                                             <option value="Tiawe">Tiawe</option>
                                             <option value="Tinamnan">Tinamnan</option>
                                         </datalist>
                                         <input type="hidden" name="address" id="adminAddress">
                                     </div>

                                    <div class="form-group row">
                                        <div class="col-sm-6 mb-3 mb-sm-0">
                                            <label for="adminContactNumber">Contact Number</label>
                                            <input type="tel" id="adminContactNumber" name="phone" pattern="[0-9]{11}" maxlength="11" required class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent input-focus transition-all duration-300"
                                                placeholder="Enter your contact number">
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="adminEmail">Email Address</label>
                                            <input type="email" id="adminEmail" name="email" required class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent input-focus transition-all duration-300"
                                                placeholder="Enter your email address">
                                            <input type="hidden" name="name" id="adminFullName">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="adminUsername" class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                                        <input type="text" id="adminUsername" name="username" required class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent input-focus transition-all duration-300"
                                                placeholder="Enter your username">
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-sm-6 mb-3 mb-sm-0">
                                            <label for="adminPassword" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                                            <div class="input-group">
                                                <input type="password" id="adminPassword" name="password" required class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent input-focus transition-all duration-300"
                                                placeholder="Enter your password">
                                                <div class="input-group-append">
                                                    <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center" onclick="togglePassword('adminPassword')">
                                                        <i class="fas fa-eye text-gray-400 hover:text-gray-600 transition-colors duration-200" id="adminPasswordIcon"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="adminConfirmPassword" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                                            <div class="input-group">
                                                <input type="password" id="adminConfirmPassword" name="password_confirmation" required class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent input-focus transition-all duration-300"
                                                placeholder="Confirm your password">
                                                <div class="input-group-append">
                                                    <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center" onclick="togglePassword('adminConfirmPassword')">
                                                        <i class="fas fa-eye text-gray-400 hover:text-gray-600 transition-colors duration-200" id="adminConfirmPasswordIcon"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="adminTermsCheckbox" name="terms_accepted" required>
                                            <label class="custom-control-label" for="adminTermsCheckbox">
                                                I agree to the <a href="#" onclick="showTerms(); return false;" class="terms-link">Terms and Conditions</a>
                                            </label>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-user btn-block text-white font-semibold py-3 px-6 rounded-xl text-lg transition-all duration-300">
                                        <i class="fas fa-user-plus mr-2"></i>
                                        Create Admin Account
                                    </button>
                                </form>
                            </div>
                        </div>

                        <br>
                        <hr>
                        <div class="text-center mt-6">
                            <a class="small" href="{{ route('login') }}"></a>
                                <p class="text-gray-600">Already have an account? 
                                    <a href="{{ route('login') }}" id="sign-in" class="font-medium" style="color: #18375d;">Sign In</a>
                                </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Terms and Conditions Modal -->
    <div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="termsModalLabel">
                        <i class="fas fa-file-contract mr-2"></i>
                        Terms and Conditions
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
  <span aria-hidden="true">&times;</span>
</button>

                </button>
                </div>
                <div class="modal-body">
                    <div class="mb-4">
                        <h6 class="text-#18375d mb-3">LBDAIRY User Agreement</h6>
                    </div>
                    
                    <p class="text-gray-700 mb-4">
                        By registering with <span class="font-semibold">LBDAIRY</span>, you agree to provide correct and up-to-date 
                        information. Keeping your details accurate ensures that the system can generate reliable records and reports 
                        that reflect your farm’s real performance.
                        The information you share will be used to monitor activities, track productivity, and provide insights that 
                        support better farm management. This allows us to give you tools and recommendations that are useful and 
                        tailored to your needs.
                    </p>

                    <p class="text-gray-700 mb-4">
                        We respect your privacy and take data protection seriously. Your information will remain secure and will only 
                        be used for the purposes stated here. We follow privacy laws to make sure your data is safe and handled 
                        responsibly at all times.
                    </p>

                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-#18375d mb-2">Data Accuracy</h6>
                            <ul class="list-unstyled">
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Update information regularly</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Report changes promptly</li>
                            </ul>
                        </div>
                        <div class="col-md-6"> 
                            <h6 class="text-#18375d mb-2">Data Usage</h6> 
                            <ul class="list-unstyled"> 
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Monitoring and analysis</li> 
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Productivity tracking</li> 
                            </ul> 
                        </div>
                    </div>
                    <div class="alert alert-info mt-3">
                        <strong>Reminder:</strong> Your data is handled according to our privacy policy and local data protection laws. Rest assured, your information is secure with us.
                        By using LBDAIRY, you accept these terms and agree to use the 
                        system honestly and responsibly.
                    </div>
                </div>
               <div class="modal-footer justify-end">
                    <button type="button" data-dismiss="modal"
                        class="btn btn-user text-white font-semibold py-2 px-4 rounded-lg text-sm transition-all duration-300">
                        I Understand and Agree
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- FontAwesome -->
     <script>
        const tabBtns = document.querySelectorAll('.tab-btn');
        const selectedRoleInput = document.getElementById('selectedRole');

        tabBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                const tab = btn.dataset.tab;
                
                // Update tab buttons
                tabBtns.forEach(b => {
                    b.classList.remove('tab-active');
                    b.classList.add('tab-inactive');
                });
                btn.classList.remove('tab-inactive');
                btn.classList.add('tab-active');
                
                // Update hidden input
                selectedRoleInput.value = tab;
                
            });
        });
    </script>

    <script>
        // Toggle password visibility
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = document.getElementById(fieldId + 'Icon');
            
            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
        
        // Show terms modal
        function showTerms() {
            console.log('showTerms function called');
            const termsModal = document.getElementById('termsModal');
            if (termsModal) {
                console.log('Terms modal found, showing...');
                $('#termsModal').modal('show');
            } else {
                console.error('Terms modal not found!');
            }
        }

        // Auto-generate full name for admin form
        document.getElementById('adminFirstName').addEventListener('input', updateAdminFullName);
        document.getElementById('adminLastName').addEventListener('input', updateAdminFullName);

        function updateAdminFullName() {
            const firstName = document.getElementById('adminFirstName').value;
            const lastName = document.getElementById('adminLastName').value;
            const fullName = `${firstName} ${lastName}`.trim();
            document.getElementById('adminFullName').value = fullName;
        }

        // Auto-populate address field from barangay selection for admin form
        document.getElementById('adminBarangay').addEventListener('input', updateAdminAddress);

        function updateAdminAddress() {
            const barangay = document.getElementById('adminBarangay').value;
            if (barangay) {
                const address = `Brgy. ${barangay}, Lucban, Quezon`;
                document.getElementById('adminAddress').value = address;
            }
        }

        // Auto-generate full name for farmer form
        document.getElementById('farmerFirstName').addEventListener('input', updateFarmerFullName);
        document.getElementById('farmerLastName').addEventListener('input', updateFarmerFullName);

        function updateFarmerFullName() {
            const firstName = document.getElementById('farmerFirstName').value;
            const lastName = document.getElementById('farmerLastName').value;
            const fullName = `${firstName} ${lastName}`.trim();
            // Update the name field for farmer form
            let nameInput = document.querySelector('#farmerForm input[name="name"]');
            if (!nameInput) {
                nameInput = document.createElement('input');
                nameInput.type = 'hidden';
                nameInput.name = 'name';
                document.getElementById('farmerForm').appendChild(nameInput);
            }
            nameInput.value = fullName;
        }

        // Auto-populate address field from farm address selection for farmer form
        document.addEventListener('DOMContentLoaded', function() {
            const farmAddressField = document.getElementById('farmerFarmAddress');
            if (farmAddressField) {
                farmAddressField.addEventListener('input', updateFarmerAddress);
                farmAddressField.addEventListener('change', updateFarmerAddress);
                farmAddressField.addEventListener('blur', updateFarmerAddress);
                console.log('Event listeners added to farm address field');
            } else {
                console.error('Farm address field not found!');
            }
        });

        function updateFarmerAddress() {
            const farmAddress = document.getElementById('farmerFarmAddress').value;
            if (farmAddress) {
                const address = `Brgy. ${farmAddress}, Lucban, Quezon`;
                const addressField = document.getElementById('farmerAddress');
                if (addressField) {
                    addressField.value = address;
                }
            }
        }

        // Form validation and submission
        document.getElementById('farmerForm').addEventListener('submit', function(e) {
            // Ensure address field is populated before validation
            const farmAddress = document.getElementById('farmerFarmAddress').value;
            if (farmAddress && !document.getElementById('farmerAddress').value) {
                const address = `Brgy. ${farmAddress}, Lucban, Quezon`;
                document.getElementById('farmerAddress').value = address;
            }
            
            if (!validateForm('farmer')) {
                e.preventDefault();
            }
        });

        document.getElementById('adminForm').addEventListener('submit', function(e) {
            if (!validateForm('admin')) {
                e.preventDefault();
            }
        });

        function validateForm(type) {
            const form = document.getElementById(type + 'Form');
            const inputs = form.querySelectorAll('input[required], select[required]');
            let isValid = true;

            inputs.forEach(input => {
                if (!input.value.trim()) {
                    input.classList.add('is-invalid');
                    isValid = false;
                } else {
                    input.classList.remove('is-invalid');
                }
            });

            // Password confirmation validation
            const password = form.querySelector('input[name="password"]');
            const confirmPassword = form.querySelector('input[name="password_confirmation"]');
            
            if (password.value !== confirmPassword.value) {
                confirmPassword.classList.add('is-invalid');
                isValid = false;
            } else {
                confirmPassword.classList.remove('is-invalid');
            }

            return isValid;
        }

        // Remove validation styling on input
        document.querySelectorAll('.form-control-user').forEach(input => {
            input.addEventListener('input', function() {
                this.classList.remove('is-invalid');
            });
        });

        // Tab switching with form reset
        document.querySelectorAll('[data-toggle="tab"]').forEach(tab => {
            tab.addEventListener('shown.bs.tab', function(e) {
                // Reset forms when switching tabs
                document.getElementById('farmerForm').reset();
                document.getElementById('adminForm').reset();
                
                // Remove validation styling
                document.querySelectorAll('.is-invalid').forEach(el => {
                    el.classList.remove('is-invalid');
                });
            });
        });

        // Initialize terms modal functionality
        $(document).ready(function() {
            console.log('DOM loaded, checking terms modal...');
            const termsModal = document.getElementById('termsModal');
            if (termsModal) {
                console.log('Terms modal found in DOM');
                console.log('jQuery and Bootstrap 4 modal ready');
            } else {
                console.error('Terms modal not found in DOM');
            }
        });
    </script>
</body>
</html>