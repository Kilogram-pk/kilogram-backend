<?php

namespace App\Http\Controllers\docs;

use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     * path="/api/auth/register",
     * summary="Register",
     * description="Register user by either email/phone",
     * operationId="authRegister",
     * tags={"Auth"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass user credentials",
     *    @OA\JsonContent(
     *       required={"email","phone"},
     *       @OA\Property(property="email", type="string", format="email", example="user@mail.com"),
     *       @OA\Property(property="phone", type="integer", format="string", example="+923215508055"),
     *    ),
     * ),
     *   @OA\Response(
     *     response=200,
     *     description="Success or success with email verification sent",
     *     @OA\JsonContent(
     *        @OA\Property(property="", type="object", example={
                "saved": true,
                "message": "user->email ? A key has been sent to your email to verify : User created successfully"
                }),
     *     )
     *  ),
     *
     * @OA\Response(
     *    response=422,
     *    description="Wrong input response",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="The given data was invalid.")
     *        )
     *     )
     * )
     */

    /**
     * @OA\Post(
     * path="/api/auth/register-info",
     * summary="Register user info",
     * description="Register user info for name, username, password",
     * operationId="authRegisterInfo",
     * tags={"Auth"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass user credentials, for param user send either phone or email of user",
     *    @OA\JsonContent(
     *       required={"username","password", "user"},
     *       @OA\Property(property="user", type="string", format="string", example="test@kilogram.ga"),
     *       @OA\Property(property="username", type="string", format="string", example="hasnatsafder"),
     *       @OA\Property(property="name", type="string", format="string", example="Hasnat Safder"),
     *       @OA\Property(property="password", type="string", format="password", example="123456"),
     *    ),
     * ),
     *   @OA\Response(
     *     response=200,
     *     description="Success or success with email verification sent",
     *     @OA\JsonContent(
     *        @OA\Property(property="", type="object", example={
                                                    "saved": true,
                                                    "message": "Data added successfully"
                                                    }),
     *     )
     *  ),
     *
     * @OA\Response(
     *    response=401,
     *    description="User not found for this email",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="object", example={
                    "success": false,
                    "message": "User not found"
                    })
     *        )
     *     )
     * )
     *
     * @OA\Response(
     *    response=422,
     *    description="Wrong input response",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="The given data was invalid.")
     *        )
     *     )
     * )
     */

    /**
     * @OA\Post(
     * path="/api/auth/social-login/facebook",
     * summary="Social login",
     * description="Register user from facebook token",
     * operationId="authSocialLogin",
     * tags={"Auth"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass facebook token",
     *    @OA\JsonContent(
     *       required={"token"},
     *       @OA\Property(property="token", type="string", format="string", example="EAAPTe5h66KgBAMBC34mi9fZCgVH5SLccUM15TC4EXHZBQhqHgVkTha13WLOOgdHFV1IjwEQLZBvDJDDYSnztdBtoyYv6akisfB1rB4EY6LtcWZBFLomOaNM9xQWDaI6DHwfCvQaPiK41Yz7GNJhubAIKYM68ZAR88mJO0D2ZBCZCDxkdOpPISbcSLI4ZBHTbGYQ3AaM5U8t6xYENTzfUSzlCWBz8IlbUb5EZD"),
     *    ),
     * ),
     *   @OA\Response(
     *     response=200,
     *     description="Successfully added social user",
     *     @OA\JsonContent(
     *        @OA\Property(property="", type="object", example={
                    "access_token": "xyz",
                    "token_type": "Bearer",
                    "refresh_token": null,
                    "expires_at": "2020-10-09 11:42:46"
                    }),
     *     )
     *  ),
     * @OA\Response(
     *    response=401,
     *    description="User not found for this token",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="object", example={
                "success": false,
                "message": "User not found for this token"
                })
     *        )
     *     )
     * )
     */

    /**
     * @OA\Post(
     * path="/api/auth/verify-code",
     * summary="Verify code",
     * description="Verify email code",
     * operationId="authVerifyCode",
     * tags={"Auth"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass email and Verification key",
     *    @OA\JsonContent(
     *       required={"email", "verification_key"},
     *       @OA\Property(property="email", type="string", format="email", example="test@kilogram.ga"),
     *       @OA\Property(property="verification_key", type="string", format="string", example="985654"),
     *    ),
     * ),
     *   @OA\Response(
     *     response=200,
     *     description="Verification successful",
     *     @OA\JsonContent(
     *        @OA\Property(property="", type="object", example={
                    "success": true,
                    "message": "Verification successful"
                    }),
     *     )
     *  ),
     *
     * @OA\Response(
     *    response=401,
     *    description="User not found for this token",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="object", example={
                "success": false,
                "message": "Verification code not found"
                })
     *        )
     *     )
     * )
     */

    /**
     * @OA\Post(
     * path="/api/auth/renew-code",
     * summary="Renew verifcation code",
     * description="Renew email code",
     * operationId="authRenewCode",
     * tags={"Auth"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass email",
     *    @OA\JsonContent(
     *       required={"email"},
     *       @OA\Property(property="email", type="string", format="email", example="test@kilogram.ga"),
     *    ),
     * ),
     *   @OA\Response(
     *     response=201,
     *     description="Verification code renewed",
     *     @OA\JsonContent(
     *        @OA\Property(property="", type="object", example={
                "success": true,
                "message": "Verification code renewed"
                }),
     *     )
     *  ),
     *
     * @OA\Response(
     *    response=409,
     *    description="User already verified",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="object", example={
                "success": false,
                "message": "User has already been verified"
                })
     *        )
     * ),
     *
     * @OA\Response(
     *    response=401,
     *    description="User not found for this email",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="object", example={
                            "success": false,
                            "message": "User not found"
                        })
     *        )
     *     )
     * )
     */

    /**
     * @OA\Post(
     * path="/api/auth/login",
     * summary="Login user",
     * description="Login user to recieve access token",
     * operationId="authLogin",
     * tags={"Auth"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass user's email/username/phone and password",
     *    @OA\JsonContent(
     *       required={"user", "password"},
     *       @OA\Property(property="user", type="string", format="sting", example="test@kilogram.ga"),
     *       @OA\Property(property="password", type="password", format="password", example="Pass123"),
     *    ),
     * ),
     *   @OA\Response(
     *     response=200,
     *     description="Login successfull",
     *     @OA\JsonContent(
     *        @OA\Property(property="", type="object", example={
                "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiMDJkNmQyODYxMDY4ZDNjZDgwM2I5Njc2ZjhmMDZiMTkxYjhkMGM1YjhlODhlNWYxZTA2NjMwNTRhZGQ2NmRhZmU4MDViNDg4NDY1NDBjMGMiLCJpYXQiOjE2MDIyNDg0OTMsIm5iZiI6MTYwMjI0ODQ5MywiZXhwIjoxNjMzNzg0NDkzLCJzdWIiOiI0Iiwic2NvcGVzIjpbXX0.vxETPZTyJJ797BXJqmPsMjq3js7ru4yhbPwIG95cQx230L1H3ozsQqFYAsHdI26s1psrtKAin3_4OAzXoGGZ35Yor5yXfHul1Fymr401TgLXoHUuwpwIKeuueX4dkrutLX55xZFFbohHa_prVrJjTX3yB9DviA7q0KnFu1DGk---OlCedao8dSGRXTAkv4FQe8RcPe2U9BNzs9G843Dj06hVBhZYlAt2keMzAsWjU9K_29wiwGE0_B2tdP42Ms6-8JGt5QWHiG4fPVUIlgAH1n94wdHbV2D1WVVhM7Cpkl6efIjP4of4utg9vx7ocs2q5dzdFf7a7LYR1m2wsoXkABeo7AtO6oqOeETBox90nsxm6MREkTKjEaN6tG9Cyp5uxnlywsllIXMhWEGhLv8jOhNwsOhJ31QYmKsiPjknIJUgVZVFrJ3Ar39huCZCVSe9Z4wvecHM6yHRO-z3c8ZoDA_p9lSvSEf_KjLMa4QZxI2Z3hrvoN7Jrvb-XBKmXvndooLXKj8FUo0a9ACFdUdDmwUkKc9uV2o7ULpIfEP2kwZksPOM08qEDcfvYSO9jCuXNlyhtfO0YI17pazdfRWUHsR1k8bOQUn4-nbhK-0LTeX4kbfH1vwD0Veugsa1KWi5SWw8-f5aIOr93W-nglaIulwl7moxZpIhfZwlXAq0Ncc",
                "token_type": "Bearer",
                "refresh_token": null,
                "expires_at": "2020-10-09 13:01:33"
                }),
     *     )
     *  ),
     *
     * @OA\Response(
     *    response=409,
     *    description="User already verified",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="object", example={
                "success": false,
                "message": "User has already been verified"
                })
     *        )
     * ),
     *
     * @OA\Response(
     *    response=401,
     *    description="User already verified",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="object", example={
                "success": false,
                "message": "User has already been verified"
                })
     *        )
     *     )
     * )
     */

    /**
     * @OA\Post(
     * path="/api/auth/forgot-password",
     * summary="Forgot password email",
     * description="Request an email to reset password",
     * operationId="authForgotPassword",
     * tags={"Auth"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass user's email",
     *    @OA\JsonContent(
     *       required={"email"},
     *       @OA\Property(property="email", type="string", format="email", example="test@kilogram.ga")
     *    ),
     * ),
     *   @OA\Response(
     *     response=200,
     *     description="Login successfull",
     *     @OA\JsonContent(
     *        @OA\Property(property="", type="object", example={
                            "success": true,
                            "message": "An reset email link has been sent"
                        }),
     *     )
     *  ),
     *
     * @OA\Response(
     *    response=403,
     *    description="User not verified yet",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="object", example={
                "success": false,
                "message": "User not verified yet"
                })
     *        )
     * ),
     *
     * @OA\Response(
     *    response=401,
     *    description="User not found",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="object", example={
                "success": false,
                "message": "User not found"
                })
     *        )
     *     )
     * )
     */

    /**
     * @OA\Get(
     * path="/api/auth/validate_token/{token}",
     * summary="Validate token",
     * description="Validate a reset token",
     * operationId="authValidateToken",
     * tags={"Auth"},
     * @OA\Parameter (
     *    description="Token",
     *    in="path",
     *    name="token",
     *    required=true,
     *    example="1nOsTmJM4Ne7d8cqyG5aeJAPh49oSC7cj3OYmiC4fyCQhpxg3TEwD6ZJnmji",
     * ),
     *   @OA\Response(
     *     response=200,
     *     description="Valid token",
     *     @OA\JsonContent(
     *        @OA\Property(property="", type="object", example={
                "success": true,
                "message": "Valid token"
                }),
     *     )
     *  ),
     *
     * @OA\Response(
     *    response=404,
     *    description="Token not found",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="object", example={
                "success": false,
                "message": "Token not found"
                })
     *        )
     *      ),
     *
     * )
     */

    /**
     * @OA\Post(
     * path="/api/auth/reset-password",
     * summary="Reset Password",
     * description="Reset to a new password",
     * operationId="authResetPassword",
     * tags={"Auth"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass user's email, token, password and password_confirmation",
     *    @OA\JsonContent(
     *       required={"email", "token", "password", "password_confirmation"},
     *       @OA\Property(property="email", type="string", format="email", example="test@kilogram.ga"),
     *       @OA\Property(property="token", type="string", format="string", example="1nOsTmJM4Ne7d8cqyG5aeJAPh49oSC7cj3OYmiC4fyCQhpxg3TEwD6ZJnmji"),
     *       @OA\Property(property="password", type="password", format="password", example="123456"),
     *       @OA\Property(property="password_confirmation", type="password", format="password", example="123456"),
     *    ),
     * ),
     *   @OA\Response(
     *     response=200,
     *     description="Password updated",
     *     @OA\JsonContent(
     *        @OA\Property(property="", type="object", example={
                            "success": true,
                            "message": "Password updated"
                        }),
     *     )
     *  ),
     *
     * @OA\Response(
     *    response=404,
     *    description="Token not found",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="object", example={
                "success": false,
                "message": "Token not found"
                })
     *        )
     *     )
     * )
     */

    /**
     * @OA\Get(
     * path="/api/auth/check-availability/{key}/{value}",
     * summary="Check for availability",
     * description="Check for availability of username, email or phone",
     * operationId="checkAvailability",
     * tags={"Auth"},
     * @OA\Parameter (
     *    description="key",
     *    in="path",
     *    name="key",
     *    required=true,
     *    example="email",
     * ),
     * @OA\Parameter (
     *    description="value",
     *    in="path",
     *    name="value",
     *    required=true,
     *    example="test@kilogram.ga",
     * ),
     *   @OA\Response(
     *     response=202,
     *     description="Available",
     *     @OA\JsonContent(
     *        @OA\Property(property="", type="object", example={
                "success": true,
                "message": "Available to use"
                }),
     *     )
     *  ),
     *
     * @OA\Response(
     *    response=422,
     *    description="Key can only be phone, email and username",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="object", example={
                "success": false,
                "message": "Key can only be phone, email and username"
                })
     *        )
     *   ),
     * @OA\Response(
     *    response=401,
     *    description="Already in use so not available",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="object", example={
                "success": false,
                "message": "Already in use"
                })
     *        )
     *      ),
     *  ),
     * )
     */
}
