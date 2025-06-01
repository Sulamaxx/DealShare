<?php
namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UsersController extends Controller
{
    public function codeGenerator()
    {
        return view('backend.aiapplication/codeGenerator');
    }

    public function addUser()
    {
        if (Auth::user()->user_type === 'moderator') {
            return redirect('/admin/login');
        }
        return view('backend.users/addUser');
    }

    // public function usersGrid()
    // {
    //     return view('backend.users/usersGrid');
    // }

    public function store(Request $request)
    {
        if (Auth::user()->user_type === 'moderator') {
            return redirect('/admin/login');
        }

        // Validate the form data
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|min:8|confirmed',
            'user_type' => 'required|in:user,moderator',
            'image'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // File validation
        ]);

        // Handle image upload if any
        if ($request->hasFile('image')) {
            $path      = $request->file('image')->store('profile-photos', 'public');
            $imagePath = $path;
        } else {
            $imagePath = null;
        }

        // Create new user
        $user = User::create([
            'name'               => $validated['name'],
            'email'              => $validated['email'],
            'password'           => Hash::make($validated['password']),
            'user_type'          => $validated['user_type'],
            'profile_photo_path' => $imagePath, // Store image URL if available
        ]);

        $appName = config('app.name');
        $subject = 'Welcome to ' . $appName . '! Your Account Has Been Created.';

        $body = "# Hello **{$user->name}**,\n\n";
        $body .= "Welcome to **{$appName}**! Your account has been successfully created.\n\n";
        $body .= "Here are your account details:\n";
        $body .= "* **Username:** {$user->name}\n";
        $body .= "* **Email:** {$user->email}\n";

        $body .= "\n";
        $body .= "You can now log in to your account and start exploring.\n\n";

                                   // Assuming you have a login route
        $loginUrl = url('/login'); // Adjust this to your actual login route

        // Suggesting password reset for security
        $body .= "If this account was created for you, we recommend resetting your password immediately by clicking 'Forgot Your Password?' on the login page.\n\n";

        $body .= "We're excited to have you on board!\n\n";

        if ($user->email) {
            send_generic_email($user->email, $subject, $body, $loginUrl, "Log In to Your Account");
        }

        // Redirect to the users list page with a success message
        return redirect()->route('usersList')->with('success', 'User created successfully');
    }

    public function usersList(Request $request)
    {
        if (Auth::user()->user_type === 'moderator') {
            return redirect('/admin/login');
        }

        $query = User::query();

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->has('status') && ($request->input('status') === '0' || $request->input('status') === '1')) {
            $query->where('status', $request->status);
        }

        $users = $query->where('user_type', 'user')->orWhere('user_type', 'moderator')->orderBy('created_at', 'desc')->paginate(8);

        return view('backend.users/usersList', compact('users'));
    }
    public function reportedUsersList(Request $request)
    {
        if (Auth::user()->user_type === 'moderator') {
            return redirect('/admin/login');
        }

        $query = User::query();

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->where('user_type', 'user')->where(function ($q) {
            $q->where('post_report_count', '>', 0)
                ->orWhere('comment_report_count', '>', 0);
        })->where('status', 1)->orderBy('created_at', 'desc')->paginate(8);

        return view('backend.users/reportedUsersList', compact('users'));
    }

    public function bannedUsersList(Request $request)
    {
        if (Auth::user()->user_type === 'moderator') {
            return redirect('/admin/login');
        }

        $query = User::query();

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $users = $query->where('user_type', 'user')->where(function ($q) {
            $q->where('status', 2)->orWhere('status', 3);
        })->orderBy('created_at', 'desc')->paginate(8);

        return view('backend.users/bannedUsersList', compact('users'));
    }

    public function viewProfile($id)
    {
        if (Auth::user()->user_type === 'moderator') {
            return redirect('/admin/login');
        }

        $user = User::findOrFail($id);
        return view('backend.users/viewProfile', compact('user'));
    }

    public function changeStatus($id)
    {
        if (Auth::user()->user_type === 'moderator') {
            return redirect('/admin/login');
        }

        $user         = User::findOrFail($id);
        $oldStatus    = $user->status;
        $newStatus    = $oldStatus == 1 ? 0 : 1;
        $user->status = $newStatus;
        $user->save();

        $subject    = '';
        $body       = '';
        $buttonUrl  = null;
        $buttonText = null;

        if ($newStatus == 1) { // User is now active (status 1)
            $subject = 'Your Account Has Been Activated on ' . config('app.name');
            $body    = "
# Hello **{$user->name}**,

Good news! Your account on **" . config('app.name') . "** has been **activated**.

You can now log in and access all features of our platform.


Thanks,<br>
The Team at " . config('app.name') . "
";
            /* $buttonUrl = url('/login');
            $buttonText = 'Login to Your Account'; */
        } else { // User is now deactivated (status 0)
            $subject = 'Important: Your Account Status on ' . config('app.name');
            $body    = "
# Hello **{$user->name}**,

This is an important notification regarding your account on **" . config('app.name') . "**." . "
Your account has been **deactivated**.

This might be due to a policy violation, inactivity, or an administrative decision. If you believe this is a mistake or have any questions, please contact our support team.

Contact our support team at [info@buyme.lk].

Thanks,<br>
The Team at " . config('app.name') . "
";
            // No button for deactivation, as they might not be able to log in
            // $buttonUrl = null;
            // $buttonText = null;
        }
        send_generic_email($user->email, $subject, $body, null, null);
        return redirect()->back()->with('success', 'User status updated successfully!');
    }

    public function destroy($id)
    {
        if (Auth::user()->user_type === 'moderator') {
            return redirect('/admin/login');
        }

        $user = User::findOrFail($id);

        $userEmail = $user->email;
        $userName  = $user->name;
        $appName   = config('app.name');

        $user->delete();

        $subject = 'Important: Your Account Has Been Deleted from ' . $appName;

        $body = "
# Hello **{$userName}**,

This is an important notification to inform you that your account on **{$appName}** has been **deleted**.

This action was taken due to a violation of our terms of service, at your request, or for other administrative reasons. If you believe this is a mistake or have any questions regarding this action, please contact our support team immediately.

Contact our support team at [info@buyme.lk].

Thank you for your understanding.<br>
The Team at {$appName}
";

        send_generic_email($userEmail, $subject, $body, null, null);

        return redirect()->back()->with('success', 'User deleted successfully!');
    }

    public function sendWarningEmail($id)
    {
        $user = User::findOrFail($id);
        /* try {
            Mail::to($user->email)->send(new WarningEmail($user));
            return back()->with('success', 'Warning email sent to user.');
        } catch (\Exception $e) {
            Log::error('Mail Send Failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to send warning email.');
        } */
        $subject = 'Important Warning: Your Reporting Activity on ' . config('app.name');

        // Craft the body content for the warning email
        $body = "
# Hello **{$user->name}**,

This is an important message from **" . config('app.name') . "** regarding your recent activity on our platform, specifically concerning the content you have reported.

We've noticed a pattern in your recent reports that may constitute misuse of our reporting system. This could include, but is not limited to:
* Submitting **invalid or unfounded reports**.
* **Excessive or repetitive reports** without clear violations.
* Reporting content that does **not violate our community guidelines**.

The reporting feature is vital for maintaining a safe environment, and we rely on accurate and legitimate reports. Misuse of this system can hinder our ability to address genuine issues effectively.

Please review our [Community Guidelines](" . url('/pages?tab=guidelines') . ") to understand what constitutes a reportable offense and how to use the feature appropriately.

**Continued misuse of the reporting system may lead to consequences, including the removal of your reporting privileges or further actions on your account.**

If you believe there has been a mistake or if you have any questions, please contact our support team at [info@buyme.lk].

Thank you for your understanding and cooperation,

The Team at " . config('app.name') . "
";

        if (send_generic_email($user->email, $subject, $body, null, null)) {
            return back()->with('success', 'Warning email sent to user.');
        } else {
            return back()->with('error', 'Failed to send warning email.');
        }
    }

    public function temporaryBan($id)
    {
        if (Auth::user()->user_type === 'moderator') {
            return redirect('/admin/login');
        }

        $user         = User::findOrFail($id);
        $user->status = 2;
        $user->save();

        $appName = config('app.name');
        $subject = 'Important: Temporary Account Suspension on ' . $appName;

        $body = "
# Hello **{$user->name}**,

This is an important notification regarding your account on **{$appName}**.

Your account has been **temporarily suspended**. This means you will not be able to access certain features or the entire platform for a period of time.

This action was taken due to a violation of our community guidelines or terms of service. Please review our [Community Guidelines](" . url('/pages?tab=guidelines') . ") to understand what constitutes a violation.

We encourage you to review your recent activities and ensure compliance with our policies. Your account will be reviewed again after the temporary ban period.

If you believe this is a mistake or have any questions, please contact our support team at [info@buyme.lk].

Thank you for your understanding and cooperation,<br>
The Team at {$appName}
";

        send_generic_email($user->email, $subject, $body, null, null);

        return back()->with('success', 'User temporarily banned.');
    }

    public function banUser($id)
    {
        if (Auth::user()->user_type === 'moderator') {
            return redirect('/admin/login');
        }

        $user         = User::findOrFail($id);
        $user->status = 3;
        $user->save();

        $appName = config('app.name');
        $subject = 'Important: Permanent Account Ban on ' . $appName;

        $body = <<<MARKDOWN
# Hello **{$user->name}**,

This is an important notification regarding your account on **{$appName}**.

Your account has been **permanently banned**. This means you will no longer be able to access any features or the platform.

This action was taken due to severe violations of our community guidelines or terms of service. We have determined that your activities on our platform are not compatible with our community standards.

If you believe this is a mistake or have questions, you may contact our support team at [info@buyme.lk](mailto:info@buyme.lk). However, please note that permanent bans are typically irreversible.

Thank you for your understanding.
The Team at {$appName}
MARKDOWN;

        send_generic_email($user->email, $subject, $body, null, null);

        return back()->with('success', 'User permanently banned.');
    }

    public function activate($id)
    {
        if (Auth::user()->user_type === 'moderator') {
            return redirect('/admin/login');
        }

        $user         = User::findOrFail($id);
        $user->status = 1; // Set status to Active
        $user->save();

        $appName = config('app.name');
        $subject = 'Your Account Has Been Activated on ' . $appName;

        $body = "
# Hello **{$user->name}**,

Good news! Your account on **{$appName}** has been **activated**.

You can now log in and access all features of our platform. We're excited to have you back!


If you have any questions, feel free to contact our support team at [info@buyme.lk].

Thanks,<br>
The Team at {$appName}
";

        send_generic_email($user->email, $subject, $body);

        return redirect()->back()->with('success', 'User account activated.');
    }

    public function show($id)
    {
        if (Auth::user()->user_type === 'moderator') {
            return redirect('/admin/login');
        }

        $query = User::query();

        $user = $query->findOrFail($id);
        return view('backend.users.viewUser', compact('user'));
    }

    public function update(Request $request, $id) // Using Route Model Binding
    {

        if (Auth::user()->user_type === 'moderator') {
            return redirect('/admin/login');
        }

        $user = User::findOrFail($id);

        $originalName             = $user->name;
        $originalEmail            = $user->email;
        $originalUserType         = $user->user_type;
        $originalProfilePhotoPath = $user->profile_photo_path;

        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'user_type' => 'required|in:user,moderator',
            'image'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        try {
            $data = $request->only(['name', 'email', 'user_type']);

            // --- Handle Profile Photo Upload ---
            if ($request->hasFile('image')) {
                // Delete the old profile photo if it exists
                if ($user->profile_photo_path && Storage::disk('public')->exists($user->profile_photo_path)) {
                    Storage::disk('public')->delete($user->profile_photo_path);
                }
                $path                       = $request->file('image')->store('profile-photos', 'public');
                $data['profile_photo_path'] = $path; // Add the new path to the data array
            }

            /* $data['is_private'] = $request->has('is_private');
            $data['email_thread_reply'] = $request->has('email_thread_reply');
            $data['email_thread_reply_like'] = $request->has('email_thread_reply_like');
            $data['email_mention'] = $request->has('email_mention'); */

            $user->update($data);

            $appName = config('app.name');
            $subject = 'Profile Updated Successfully on ' . $appName;

            $body = "# Hello **{$user->name}**,\n\n";
            $body .= "This is a notification to inform you that your profile on **{$appName}** has been updated successfully.\n\n";
            $body .= "The following information was changed:\n\n";

            // List only the fields that actually changed
            if ($user->name !== $originalName) {
                $body .= "* **Name:** From `{$originalName}` to `{$user->name}`\n";
            }
            if ($user->email !== $originalEmail) {
                $body .= "* **Email:** From `{$originalEmail}` to `{$user->email}`\n";
            }
            if ($user->user_type !== $originalUserType) {
                $body .= "* **User Type:** From `{$originalUserType}` to `{$user->user_type}`\n";
            }
            // Check if profile photo path actually changed (means a new image was uploaded or existing was removed)
            if ($user->profile_photo_path !== $originalProfilePhotoPath) {
                $body .= "* **Profile Photo:** Updated\n";
            }

            // Add a message if no specific changes were detected (might happen if only non-tracked fields changed)
            if (empty(trim(str_replace(['# Hello **', '**', 'This is a notification to inform you that your profile on **', '** has been updated successfully.', 'The following information was changed:', 'Thank you,The Team at '], '', $body)))) {
                $body .= "Some details of your profile have been updated.\n";
            }

            $body .= "\nThese changes have been made by the **Buyme Bargains Team**. If you have any questions, please contact our support team immediately at [info@buyme.lk].\n\n";
            $body .= "Thank you,\nThe Team at {$appName}";

            send_generic_email($user->email, $subject, $body, null, null);

            return redirect()->route('usersList')->with('success', 'Profile settings updated successfully!');
        } catch (Exception $e) {
            // --- Log the error and set an error flash message ---
            Log::error("Error updating user settings for user {$user->id}: " . $e->getMessage());
            return redirect()->route('usersList')->with('error', 'Failed to update profile settings. Please try again.');
        }
    }
}
