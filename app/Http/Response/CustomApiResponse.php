<?php

/** @noinspection ALL */

namespace App\Http\Response;

use Symfony\Component\HttpFoundation\Response as ResponseHTTP;

class CustomApiResponse
{
    public function getResponseStructure($success = false, $payload = null, $message = '')
    {
         if (!empty($success) && !empty($payload)) {
            $data = [
                'message' => $message,
                'data' => $payload
            ];
        } elseif ($success) {
            $data = [
                'message' => $message,
            ];
        } else {
            $data = [
                'error' => [
                     $message,
                ]
            ];
        }

        return $data;

        //json_encode($data);
    }

    /**
     * handle all type of exceptions
     * @param \Exception $ex
     * @return mixed|string
     */
    public function handleAndResponseException(\Exception $ex)
    {
        $response = '';
        switch (true) {
            case $ex instanceof \Illuminate\Database\Eloquent\ModelNotFoundException:
                $response = response()->json(['message' => trans('message.record_not_found')], ResponseHTTP::HTTP_NOT_FOUND);
                break;
            case $ex instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException:
                $response = response()->json(['message' => trans('message.not_found')], ResponseHTTP::HTTP_NOT_FOUND);
                break;
            case $ex instanceof \Illuminate\Database\QueryException:
                $response = response()->json(['message' => trans('message.wrong_with_query')], ResponseHTTP::HTTP_BAD_REQUEST);
                break;
            case $ex instanceof \Illuminate\Http\Exceptions\HttpResponseException:
                $response = response()->json(['error' => trans('message.wrong_with_system')], ResponseHTTP::HTTP_INTERNAL_SERVER_ERROR);
                break;
            case $ex instanceof \Illuminate\Validation\ValidationException:
                $response = response()->json(['message' => trans('message.invalid_data')], ResponseHTTP::HTTP_BAD_REQUEST);
                break;
            case $ex instanceof \App\Exceptions\InvalidOtpException:
                $response = response()->json(['message' => trans('message.invalid_otp_user')], ResponseHTTP::HTTP_BAD_REQUEST);
                break;
            case $ex instanceof \App\Exceptions\TwilioException:
                $response = response()->json(['message' => trans('message.number_not_verified')], ResponseHTTP::HTTP_BAD_REQUEST);
                break;
            case $ex instanceof \App\Exceptions\MailSendException:
                $response = response()->json(['message' => trans('message.error_sending_email')], ResponseHTTP::HTTP_INTERNAL_SERVER_ERROR);
                break;
            case $ex instanceof \App\Exceptions\InvalidCredentialsException:
                $response = response()->json(['message' => trans('message.failed')], ResponseHTTP::HTTP_BAD_REQUEST);
                break;
            case $ex instanceof \App\Exceptions\CouponUsedLimitException:
                $response = response()->json(['message' => trans('message.coupon_used_limit')], ResponseHTTP::HTTP_BAD_REQUEST);
                break;
            case $ex instanceof \App\Exceptions\MinimumOrderException:
                $response = response()->json(['message' => trans('message.minimum_order_value')." ".$ex->getMessage()." Rs.!"], ResponseHTTP::HTTP_BAD_REQUEST);
                break;
            case $ex instanceof \App\Exceptions\UserCouponUsedException:
                $response = response()->json(['message' => trans('message.user_coupon_used_limit')], ResponseHTTP::HTTP_BAD_REQUEST);
                break;
            case $ex instanceof \App\Exceptions\ItemExistsException:
                $response = response()->json(['message' => $ex->getMessage()], ResponseHTTP::HTTP_BAD_REQUEST);
                break;
            case $ex instanceof \App\Exceptions\VerifyPaymentException:
                $response = response()->json(['message' => $ex->getMessage()], ResponseHTTP::HTTP_BAD_REQUEST);
                break;
            case $ex instanceof \App\Exceptions\NotificationException:
                $response = response()->json(['message' => trans('message.notification_not_send')], ResponseHTTP::HTTP_BAD_REQUEST);
                break;
            case $ex instanceof \App\Exceptions\RazorpayException:
                $response = response()->json(['message' => trans('message.razorpay_api')], ResponseHTTP::HTTP_BAD_REQUEST);
                break;
            case $ex instanceof \App\Exceptions\RefundPaymentException:
                $response = response()->json(['message' => trans('message.wrong_with_system')], ResponseHTTP::HTTP_BAD_REQUEST);
                break;
            case $ex instanceof \App\Exceptions\CountryCodeException:
                $response = response()->json(['message' => trans('message.country_code_does_not_match')], ResponseHTTP::HTTP_BAD_REQUEST);
                break;
            case $ex instanceof \App\Exceptions\CityInvalidException:
                $response = response()->json(['message' => trans('message.city_invalid')], ResponseHTTP::HTTP_NOT_FOUND);
                break;
            case $ex instanceof \App\Exceptions\SendEmptyNotificationException:
                $response = response()->json(['message' => trans('message.kitchen_owner_not_available')], ResponseHTTP::HTTP_NOT_FOUND);
                break;
            case $ex instanceof \App\Exceptions\KitchenOwnerRejectedException:
                $response = response()->json(['message' => trans('message.kitchen_owner_rejected')], ResponseHTTP::HTTP_NOT_FOUND);
                break;
            case $ex instanceof \App\Exceptions\KitchenOwnerBlockException:
                $response = response()->json(['message' => trans('message.kitchen_owner_block')], ResponseHTTP::HTTP_NOT_FOUND);
                break;
            case $ex instanceof \App\Exceptions\RemoveItemFromCartException:
                $response = response()->json(['message' => $ex->getMessage()." ".trans('message.remove_item_from_cart_exception')], ResponseHTTP::HTTP_NOT_FOUND);
                break;
            case $ex instanceof \App\Exceptions\CartMaximumAmountException:
                $response = response()->json(['message' => trans('message.cart_maximum_amount_message_1')."₹".config('constants.settings.cart_maximum_amount'). trans('message.cart_maximum_amount_message_2')], ResponseHTTP::HTTP_NOT_FOUND);
                break;
            case $ex instanceof \App\Exceptions\CartMinimumAmountException:
                $response = response()->json(['message' => trans('message.cart_minimum_amount_message_1')."₹".config('constants.settings.cart_minimum_amount'). trans('message.cart_minimum_amount_message_2')], ResponseHTTP::HTTP_NOT_FOUND);
                break;
            case $ex instanceof \App\Exceptions\RatingExistException:
                $response = response()->json(['message' => trans('message.rating_exist')], ResponseHTTP::HTTP_NOT_FOUND);
                break;
            case $ex instanceof \App\Exceptions\UserCancelMinTimeException:
                $response = response()->json(['message' => trans('message.user_cancel_min_time')], ResponseHTTP::HTTP_NOT_FOUND);
                break;
            case $ex instanceof \Exception:
                $response = response()->json(['message' => trans('message.wrong_with_system')], ResponseHTTP::HTTP_INTERNAL_SERVER_ERROR);
                break;
        }
        return $response;
    }
}
