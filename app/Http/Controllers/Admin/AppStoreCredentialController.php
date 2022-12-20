<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Common;
use App\Models\AppStoreCredentials;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Config;

class AppStoreCredentialController extends Controller
{
    protected $helper;
    public function __construct()
    {
        $this->helper = new Common();
    }

    public function getAppStoreCredentials()
    {
        $data['menu'] = 'settings';
        $data['settings_menu'] = 'app-store-credentials';

        $data['appStoreCredentialsForGoogle'] = $appStoreCredentialsForGoogle = AppStoreCredentials::where(['company' => 'Google'])->first();

        $data['appStoreCredentialsForApple'] = $appStoreCredentialsForApple = AppStoreCredentials::where(['company' => 'Apple'])->first();

        return view('admin.settings.appStoreCredentials', $data);
    }

    public function updateGoogleCredentials(Request $request)
    {
        $appStoreCredentialsForGoogle = AppStoreCredentials::where(['company' => $request->playstorecompany])->first();

        if (!empty($appStoreCredentialsForGoogle)) {
            $googleStoreCredentials = AppStoreCredentials::find($request->playstoreid);
            $googleStoreCredentials->has_app_credentials = isset($request->has_app_playstore_credentials) ? $request->has_app_playstore_credentials : 'No';
            $googleStoreCredentials->link = (isset($request->playstore['link'])) ? $request->playstore['link'] : '';
            $playstoreLogo = (isset($request->playstore['logo'])) ? $request->playstore['logo'] : '';

            if (!empty($playstoreLogo)) {
                $extension = strtolower($playstoreLogo->getClientOriginalExtension());
                if (in_array($extension, getFileExtensions(3))) {
                    $response = uploadImage($playstoreLogo, 'public/uploads/app-store-logos/', '236*64', $googleStoreCredentials->logo, '140*40');
                    if ($response['status'] === true) {
                        $googleStoreCredentials->logo = $response['file_name'];
                    }
                } else {
                    $this->helper->one_time_message('error', __('Invalid image format detected.'));
                }
            }
            $googleStoreCredentials->save();

        } else {
            $googleStoreCredentials = new AppStoreCredentials();
            $googleStoreCredentials->has_app_credentials = isset($request->has_app_playstore_credentials) ? $request->has_app_playstore_credentials : 'No';
            $googleStoreCredentials->link = $request->playstore['link'];
            $playstoreLogo = $request->playstore['logo'];

            if (!empty($playstoreLogo)) {
                $extension = strtolower($playstoreLogo->getClientOriginalExtension());
                if (in_array($extension, getFileExtensions(3))) {
                    $response = uploadImage($playstoreLogo, 'public/uploads/app-store-logos/', '236*64', $googleStoreCredentials->logo, '140*40');
                    if ($response['status'] === true) {
                        $googleStoreCredentials->logo = $response['file_name'];
                    }
                } else {
                    $this->helper->one_time_message('error', __('Invalid image format detected.'));
                }
            }
            $googleStoreCredentials->company = 'Google';
            $googleStoreCredentials->save();
        }
        $this->helper->one_time_message('success', __('Play store credentials updated successfully.'));
        return redirect(Config::get('adminPrefix').'/settings/app-store-credentials');
    }

    public function updateAppleCredentials(Request $request)
    {
        $appStoreCredentialsForApple = AppStoreCredentials::where(['company' => $request->appstorecompany])->first();

        if (!empty($appStoreCredentialsForApple)) {
            $appStoreCredentials = AppStoreCredentials::find($request->appstoreid);
            $appStoreCredentials->has_app_credentials = isset($request->has_app_appstore_credentials) ? $request->has_app_appstore_credentials : 'No';
            $appStoreCredentials->link = $request->applestore['link'];
            $applestoreLogo = (isset($request->applestore['logo'])) ? $request->applestore['logo'] : '';

            if (!empty($applestoreLogo)) {

                $extension = strtolower($applestoreLogo->getClientOriginalExtension());
                if (in_array($extension, getFileExtensions(3))) {
                    $response = uploadImage($applestoreLogo, 'public/uploads/app-store-logos/', '236*64', $appStoreCredentials->logo, '140*40');
                    if ($response['status'] === true) {
                        $appStoreCredentials->logo = $response['file_name'];
                    }
                } else {
                    $this->helper->one_time_message('error', __('Invalid image format detected.'));
                }
            }
            $appStoreCredentials->save();
        } else {
            $appleStoreCredentials = new AppStoreCredentials();
            $appleStoreCredentials->has_app_credentials = isset($request->has_app_appstore_credentials) ? $request->has_app_appstore_credentials : 'No';
            $appleStoreCredentials->link = $request->applestore['link'];
            $applestoreLogo = $request->applestore['logo'];

            if (!empty($applestoreLogo)) {
                $extension = strtolower($applestoreLogo->getClientOriginalExtension());
                if (in_array($extension, getFileExtensions(3))) {
                    $response = uploadImage($applestoreLogo, 'public/uploads/app-store-logos/', '236*64', $appleStoreCredentials->logo, '140*40');
                    if ($response['status'] === true) {
                        $appleStoreCredentials->logo = $response['file_name'];
                    }
                } else {
                    $this->helper->one_time_message('error', __('Invalid image format detected.'));
                }
            }
            $appleStoreCredentials->company = 'Apple';
            $appleStoreCredentials->save();
        }
        $this->helper->one_time_message('success', __('Apple store credentials updated successfully'));
        return redirect(Config::get('adminPrefix').'/settings/app-store-credentials');
    }

    public function deletePlaystoreLogo(Request $request)
    {
        $playstoreLogo = $request->playstoreLogo;

        if (isset($playstoreLogo))
        {
            $appStoreCredentialsForGoogle = AppStoreCredentials::where(['company' => $request->playstorecompany, 'logo' => $request->playstoreLogo])->first();

            if ($appStoreCredentialsForGoogle)
            {
                AppStoreCredentials::where(['company' => $request->playstorecompany, 'logo' => $request->playstoreLogo])->update(['logo' => null]);

                if ($playstoreLogo != null)
                {
                    $dir = public_path('uploads/app-store-logos/' . $playstoreLogo);
                    if (file_exists($dir))
                    {
                        unlink($dir);
                    }
                }
                $data['success'] = 1;
                $data['message'] = 'Logo Deleted Successfully!';
            }
            else
            {
                $data['success'] = 0;
                $data['message'] = "No Record Found!";
            }
        }
        echo json_encode($data);
        exit();
    }

    public function deleteAppStoreLogo(Request $request)
    {

        $appleStoreLogo = $request->appleStoreLogo;
        if (isset($appleStoreLogo))
        {
            $appStoreCredentialsForApple = AppStoreCredentials::where(['company' => $request->appstorecompany, 'logo' => $request->appleStoreLogo])->first();

            if ($appStoreCredentialsForApple)
            {
                AppStoreCredentials::where(['company' => $request->appstorecompany, 'logo' => $request->appleStoreLogo])->update(['logo' => null]);

                if ($appleStoreLogo != null)
                {
                    $dir = public_path('uploads/app-store-logos/' . $appleStoreLogo);
                    if (file_exists($dir))
                    {
                        unlink($dir);
                    }
                }
                $data['success'] = 1;
                $data['message'] = 'Logo Deleted Successfully!';
            }
            else
            {
                $data['success'] = 0;
                $data['message'] = "No Record Found!";
            }
        }
        echo json_encode($data);
        exit();
    }
}
