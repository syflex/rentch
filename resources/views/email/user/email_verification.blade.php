
    <h2> Hello {{ $user->name }}</h2>
        <p>Thanks you for signin up on <a href="rentch.ng">Rentch.ng</a>, your account has been created successfully. Please click on the activation link below to activate account!</p>

        <a href="https://rtapi.rentch.ng/api/auth/verifyemail/{{ \Crypt::encrypt($user->id)}}" class="btn btn-warning">Activate Account</a>
        