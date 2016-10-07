<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Filesystem\Factory as Storage;
use Illuminate\Filesystem\Filesystem;

class ImageController extends Controller {

    /**
     * Show Page to Upload Images for gallery
     * @return View
     */
    public function create()
    {
        return view( 'backend.event_gallery.upload_images');
    }

    /**
     * Function to upload images and populate database via XMLHTTPRequest
     *
     * @param Storage $storage
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|string
     */
	  public function store( Storage $storage, Request $request )
    {
        if ( $request->isXmlHttpRequest() )
        {
            $image = $request->file( 'image' );
            $timestamp = $this->getFormattedTimestamp();
            $savedImageName = $this->getSavedImageName( $timestamp, $image );

            $imageUploaded = $this->uploadImage( $image, $savedImageName, $storage );

            if ( $imageUploaded )
            {
                $data = [
                    'original_path' => asset( '/images/' . $savedImageName )
                ];
                return json_encode( $data, JSON_UNESCAPED_SLASHES );
            }
            return "uploading failed";
        }

    }
	
	 /**
     * @param $image
     * @param $imageFullName
     * @param $storage
     * @return mixed
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function uploadImage( $image, $imageFullName, $storage )
    {
        $filesystem = new Filesystem;
        return $storage->disk( 'image' )->put( $imageFullName, $filesystem->get( $image ) );
    }

    /**
     * @return string
     */
    protected function getFormattedTimestamp()
    {
        return str_replace( [' ', ':'], '-', Carbon::now()->toDateTimeString() );
    }
	
	 /**
     * @param $timestamp
     * @param $image
     * @return string
     */
    protected function getSavedImageName( $timestamp, $image )
    {
        return $timestamp . '-' . $image->getClientOriginalName();
    }
}
