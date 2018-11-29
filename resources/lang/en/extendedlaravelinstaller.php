<?php

return [
    /**
     *
     * Shared translations.
     *
     */
    'title' => 'Gridview System Monitor Installer',
    'next' => 'Next Step',
    'back' => 'Previous',
    'finish' => 'Install',
    'forms' => [
        'errorTitle' => 'The Following errors occurred:',
    ],

    /**
     *
     * Home page translations.
     *
     */
    'welcome' => [
        'templateTitle' => 'Welcome',
        'title'   => 'Gridview System Monitor Installer',
        'message' => 'Easy Installation and Setup Wizard.',
        'next'    => 'Check Requirements',
    ],
   
    /**
     *
     * Credentials page translations.
     *
     */
    'credentials' => [
        'templateTitle' => 'Step 4 | Credentials',
        'title' => 'Credentials',
        'form' => [
            'admin_name_required' => 'Name is required.',
            'admin_name_label' => 'Admin Name',
            'admin_name_placeholder' => 'Admin Name',
            'admin_email_required' => 'Email is required.',
            'admin_email_label' => 'Admin Email',
            'admin_email_placeholder' => 'Admin Email',
            'admin_password_required' => 'Password is required.',
            'admin_password_label' => 'Admin Password',
            'admin_password_placeholder' => 'Admin Password',
            'buttons' => [
                'setup_credentials' => 'Setup Credentials',
            ],
        ],
    ],

    'igadmincredentials' => [
        'templateTitle' => 'Step 5 | Igolgi Credentials',
        'title' => 'Igolgi Credentials',
        'form' => [
            'igadmin_name_required' => 'Name is required.',
            'igadmin_name_label' => 'Name',
            'igadmin_name_placeholder' => 'Name',
            'igadmin_email_required' => 'Email is required.',
            'igadmin_email_label' => 'Igolgi Email',
            'igadmin_email_placeholder' => 'Email',
            'igadmin_password_required' => 'Password is required.',
            'igadmin_password_label' => 'Igolgi Password',
            'igadmin_password_placeholder' => 'Password',
            'igadmin_password_confirmation_required' => 'Confirm Password is required.',
            'igadmin_password_confirmation_label' => 'Confirm Password',
            'igadmin_password_confirmation_placeholder' => 'Confirm Password',
            'buttons' => [
                'setup_credentials' => 'Setup Credentials',
            ],
        ],
    ],

    'licenses' => [
        'templateTitle' => 'Step 6 | User Licenses',
        'title' => 'User Licenses',
        'form' => [
            'user_licenses_required' => 'User license is required.',
            'user_licenses_label' => 'User license',
            'user_licenses_placeholder' => 'User license',
            'encryption_key_required' => 'Encryption Key is required.',
            'encryption_key_label' => 'Encryption Key',
            'encryption_key_placeholder' => 'Encryption Key',
            'buttons' => [
                'setup_user_licenses' => 'Setup User licenses',
            ],
        ],
    ],

];
