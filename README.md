## Installation

1. Unzip the downloaded archive
2. Copy and paste **customer-address-book-master** folder in your **projects** folder. Rename the folder to your project's name
3. In your terminal run `composer install`
4. Copy `.env.example` to `.env` and updated the configurations (mainly the database configuration)
5. In your terminal run `php artisan key:generate`
6. Run `php artisan migrate --seed` to create the database tables and seed the roles and users tables
7. Run `php artisan storage:link` to create the storage symlink (if you are using **Vagrant** with **Homestead** for development, remember to ssh into your virtual machine and run the command from there).

http://127.0.0.1:8000/customers
http://127.0.0.1:8000/projects


## Instructions

Customer and Address Book with Laravel

## Overview

This project involves creating a customer and address book using Laravel, with proper validations. Additionally, it includes creating projects and linking multiple customers to these projects.

## Features
1. **Customer List View**
2. **Customer Expanded List View**
3. **Add/Edit Customer Form**
   - Add multiple addresses
   - Delete added addresses
   - Redirect to the list view upon submission
4. **Project List View**
   - Display name, description, and related customer names
   - Edit button
   - Delete button
5. **Add/Edit Project Form**
   - Create new project fields (name and description)
   - Multi-select dropdown for customers with a search option
   - Redirect to the project list view upon submission

## Screenshots

| Dashboard | Customer | Project |
| --- | --- | --- |
| !Dashboard | !Customer | !Project |

## Instructions

1. Step 1: Set Up Laravel Project  or 
   ## CLONE - git clone https://thushanthsbm1997@bitbucket.org/customer-address-book-webco/customer-address-book-webco.git

2. Step 2: Create Models and Migrations
   Create models and migrations for Customer, Address, and Project

3. Step 3: Define Relationships in Models

4. Step 4: Create Controllers
   Create controllers for handling customers, addresses, and projects

5. Step 5: Define Routes

6. Step 6: Create Views
   Create Blade views for listing, adding, and editing customers and projects

7. Step 7: Validation

8. Step 8: AJAX for Form Submissions


### Postman or JSON Values

Postman_docx/


## Usage
Register a user or login with default user **admin@softui.com** and password **secret** OR **thushanth@thushanth.com** and password **thushanth** OR **adminthushanth@admin.com** and password **adminthushanth**from your database and start testing (make sure to run the migrations and seeders for these credentials to be available).

Besides the dashboard, the auth pages, the billing and table pages, there is also has an edit profile page. All the necessary files are installed out of the box and all the needed routes are added to `routes/web.php`. Keep in mind that all of the features can be viewed once you login using the credentials provided or by registering your own user. 


### Login
If you are not logged in you can only access this page or the Sign Up page. The default url takes you to the login page where you use the default credentials  **admin@softui.com** and password **secret** OR **thushanth@thushanth.com** and password **thushanth** OR **adminthushanth@admin.com** and password **adminthushanth**. Logging in is possible only with already existing credentials. For this to work you should have run the migrations.

The `App\Http\Controllers\SessionController` handles the logging in of an existing user.

```
       public function store()
    {
        $attributes = request()->validate([
            'email'=>'required|email',
            'password'=>'required' 
        ]);

        if(Auth::attempt($attributes))
        {
            session()->regenerate();
            return redirect('dashboard');
        }
        else{

            return back();
        }
    }
```

### Register
You can register as a user by filling in the name, email, role and password for your account. For your role you can choose between the Admin, Creator and Member. It is important to know that an admin user has access to all the pages and actions, can delete, add and edit another users, other roles, items, tags or categories; a creator user has acces to category, tag and item managemen, but can not add, edit or delete other users; a member user has access to the item management but can not take any action. You can do this by accessing the sign up page from the "**Sign Up**" button in the top navbar or by clicking the "**Sign Up**" button from the bottom of the log in form. Another simple way is adding **/register** in the url.

The `App\Http\Controllers\RegisterController` handles the registration of a new user.

```
    public function store()
    {
        $attributes = request()->validate([
            'name' => ['required', 'max:50'],
            'email' => ['required', 'email', 'max:50', Rule::unique('users', 'email')],
            'password' => ['required', 'min:5', 'max:20'],
            'agreement' => ['accepted']
        ]);
        $attributes['password'] = bcrypt($attributes['password'] );

        session()->flash('success', 'Your account has been created.');
        $user = User::create($attributes);
        Auth::login($user); 
        return redirect('/dashboard');
    }
```

### Forgot Password
If a user forgets the account's password it is possible to reset the password. For this the user should click on the "**here**" under the login form or add **/login/forgot-password** in the url.

The `App\Http\Controllers\ResetController` takes care of sending an email to the user where he can reset the password afterwards.

```
    public function sendEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
                    ? back()->with(['status' => __($status)])
                    : back()->withErrors(['email' => __($status)]);
    }
```

### Reset Password
The user who forgot the password gets an email on the account's email address. The user can access the reset password page by clicking the button found in the email. The link for resetting the password is available for 12 hours. The user must add the new password and confirm the password for his password to be updated. The user is redirected to the login page.

The `App\Http\Controllers\ChangePasswordController` helps the user reset the password.

```
    public function changePassword(Request $request)
    {
        
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);
    
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));
    
                $user->save();
    
                event(new PasswordReset($user));
            }
        );
    
        return $status === Password::PASSWORD_RESET
                    ? redirect('/login')->with('status', __($status))
                    : back()->withErrors(['email' => [__($status)]]);
    }
```

### My Profile
The profile can be accessed by a logged in user by clicking "**User Profile**" from the sidebar or adding **/user-profile** in the url. The user can add information like birthday, gender, phone number, location, language  or skills.

The `App\Http\Controllers\InfoUserController` handles the user's profile information.

```
    public function store(Request $request)
    {

        $attributes = request()->validate([
            'name' => ['required', 'max:50'],
            'email' => ['required', 'email', 'max:50', Rule::unique('users')->ignore(Auth::user()->id)],
            'phone'     => ['max:50'],
            'location' => ['max:70'],
            'about_me'    => ['max:150'],
            'email' => ['required', 'email', 'max:50', Rule::unique('users')->ignore(Auth::user()->id)],
        ]);
        
        User::where('id',Auth::user()->id)
        ->update([
            'name'    => $attributes['name'],
            'email' => $attribute['email'],
            'phone'     => $attributes['phone'],
            'location' => $attributes['location'],
            'about_me'    => $attributes["about_me"],
        ]);

        return redirect('/user-profile');
    }
