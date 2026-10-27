# FieldMA 2.0 — Flutter Mobile API Audit: Used vs. Not Used

This document provides a comprehensive audit of all API endpoints across `routes/api.php`, `routes/v2/api.php`, `routes/v3/api.php`, and `routes/v4/api.php`.

The audit is strictly cross-referenced against the active **Flutter Mobile Application** route constants.

---

## Executive Summary

| Route File          | Prefix    | Total Routes | Active (Used by Flutter) | Inactive (Not Used / Comment Out) |
| :------------------ | :-------- | :----------: | :----------------------: | :-------------------------------: |
| `routes/api.php`    | `/api`    |      38      |          **17**          |              **21**               |
| `routes/v2/api.php` | `/api/v2` |      23      |          **10**          |              **13**               |
| `routes/v3/api.php` | `/api/v3` |      10      |          **7**           |               **3**               |
| `routes/v4/api.php` | `/api/v4` |      9       |          **5**           |               **4**               |
| **Total**           |           |    **80**    |      **39** (48.8%)      |          **41** (51.2%)           |

---

## 1. Active APIs (Used by Flutter App)

These **39 endpoints** are actively consumed by the Flutter mobile application and **MUST REMAIN ACTIVE** and fully supported during and after the modular monolith restructuring.

### 1.1 Core / SFA Endpoints (`/api`)

|  #  | HTTP Method | Endpoint Path                        | Target Controller & Action                          | Flutter App Constant             | Domain / Feature       |
| :-: | :---------- | :----------------------------------- | :-------------------------------------------------- | :------------------------------- | :--------------------- |
|  1  | `GET`       | `/api/home`                          | `API\MobileuserController@home`                     | `HOME`                           | Field Staff Dashboard  |
|  2  | `GET`       | `/api/dealers`                       | `API\DealerController@index`                        | `DEALERS`                        | Dealer Directory       |
|  3  | `GET`       | `/api/countAmountandVisit`           | `API\PaymentController@index`                       | `COUNT_AMOUNT_AND_VISIT`         | Daily Metrics          |
|  4  | `GET`       | `/api/collectionDetails/{id}`        | `API\NoticeController@collectionDetails`            | `COLLECTION_HISTORY_DETAILS`     | Payment Collections    |
|  5  | `GET`       | `/api/dealers/position`              | `API\DealerController@position`                     | `DEALER_POSITION`                | Dealer Geolocation     |
|  6  | `GET`       | `/api/dealers/reposition`            | `API\DealerController@reposition`                   | `DEALER_REPOSITION`              | Dealer Repositioning   |
|  7  | `GET`       | `/api/dealerWithboundry`             | `API\DealerController@dealerWithboundry`            | `DEALER_WITH_BOUNDARY`           | Geofenced Dealers      |
|  8  | `POST`      | `/api/session`                       | `API\SessionController@store`                       | `OFFLINE_VISIT` / `ONLINE_VISIT` | Visit Sessions         |
|  9  | `GET`       | `/api/visit`                         | `API\MobileuserController@schedule`                 | `VISIT_HISTORY`                  | Visit Schedules        |
| 10  | `GET`       | `/api/rangehistory`                  | `API\MobileuserController@rangehistory`             | `VISIT_HISTORY_RANGE`            | Historical Visits      |
| 11  | `GET`       | `/api/contractors`                   | `API\v2\SiteRegistrationController@indexContractor` | `PAINT_CONTRACTOR`               | Contractor Directory   |
| 12  | `POST`      | `/api/contractors`                   | `API\v2\SiteRegistrationController@storeContractor` | `PAINT_CONTRACTOR`               | Create Contractor      |
| 13  | `GET`       | `/api/my-teams`                      | `API\MyTeamController@index`                        | `myTeams`                        | Team Hierarchy         |
| 14  | `GET`       | `/api/my-team/{user}/{date}`         | `API\MyTeamController@show`                         | `myTeam`                         | Team Member Activity   |
| 15  | `GET`       | `/api/tada/authorized-users`         | `API\TadaReportController@authorizedUsers`          | `TADA_AUTHORIZED_USERS`          | TADA Approvers         |
| 16  | `GET`       | `/api/tada/expense-authorized-users` | `API\TadaReportController@expenseAuthorizedUsers`   | `TADA_EXPENSE_AUTHORIZED_USER`   | TADA Expense Approvers |
| 17  | `POST`      | `/api/v5/negotiation`                | `API\SessionController@v5negotiation`               | `UNLISTED_VISIT_V5`              | Unlisted Dealer Visit  |
| 18  | `POST`      | `/api/device-log`                    | `API\DeviceLogController@store`                     | Not used in Flutter              |

### 1.2 TADA Expense Claims (`/api/tada*`)

|  #  | HTTP Method | Endpoint Path                          | Target Controller & Action                    | Flutter App Constant         | Domain / Feature      |
| :-: | :---------- | :------------------------------------- | :-------------------------------------------- | :--------------------------- | :-------------------- |
| 18  | `GET`       | `/api/tada`                            | `API\TadaReportController@index`              | `TADA`                       | TADA Claims List      |
| 19  | `POST`      | `/api/tada`                            | `API\TadaReportController@store`              | `TADA`                       | Submit TADA Claim     |
| 20  | `GET`       | `/api/tada/{id}`                       | `API\TadaReportController@show`               | `TADA`                       | TADA Claim Details    |
| 21  | `PUT`       | `/api/tada/{id}`                       | `API\TadaReportController@update`             | `TADA`                       | Update TADA Claim     |
| 22  | `DELETE`    | `/api/tada/{id}`                       | `API\TadaReportController@destroy`            | `TADA`                       | Cancel TADA Claim     |
| 23  | `POST`      | `/api/tada/upload-image`               | `API\TadaReportController@uploadImage`        | Supporting TADA upload       | Expense Receipt Proof |
| 24  | `DELETE`    | `/api/tada-expenses/images/{image_id}` | `API\TadaReportController@deleteExpenseImage` | `deleteTadaExpenseImage(id)` | Delete Receipt Image  |

### 1.3 Version 2 Endpoints (`/api/v2`)

|  #  | HTTP Method    | Endpoint Path                          | Target Controller & Action                   | Flutter App Constant        | Domain / Feature     |
| :-: | :------------- | :------------------------------------- | :------------------------------------------- | :-------------------------- | :------------------- |
| 25  | `GET`          | `/api/v2/attendance`                   | `API\v2\AttendanceController@index`          | `GET_ATTENDANCE`            | Attendance History   |
| 26  | `POST`         | `/api/v2/attendance`                   | `API\v2\AttendanceController@store`          | `GET_ATTENDANCE` (Clock-in) | Attendance Ingestion |
| 27  | `GET`          | `/api/v2/notice`                       | `API\v2\NoticeController@index`              | `NOTICE`                    | Broadcast Notices    |
| 28  | `GET`          | `/api/v2/notice/{id}`                  | `API\v2\NoticeController@individual`         | `getNotice(id, page)`       | Notice Details       |
| 29  | `GET`          | `/api/v2/products`                     | `API\v2\ProductController@index`             | `GET_PRODUCT`               | Product Catalog      |
| 30  | `GET`          | `/api/v2/collectionHistory`            | `API\v2\PaymentController@collectionHistory` | `COLLECTION_HISTORY`        | Payment History      |
| 31  | `PATCH`        | `/api/v2/updatestatus/{id}`            | `API\v2\PaymentController@updatestatus`      | `UPDATE_STATUS`             | Payment Status       |
| 32  | `GET`          | `/api/v2/visitSchedule`                | `API\v2\MobileuserController@visitSchedule`  | `SCHEDULE_VISIT`            | Assigned Visit Plan  |
| 33  | `GET`, `POST`  | `/api/v2/site-registration`            | `API\v2\SiteRegistrationController`          | `SITE_REGISTRATION`         | Site Pipeline        |
| 34  | `PATCH`        | `/api/v2/site-registration/{followUp}` | `API\v2\SiteRegistrationController@update`   | `ADD_FOLLOWUP`              | Follow-up Update     |
| 35  | `GET`, `PATCH` | `/api/v2/site-products`                | `API\v2\SiteRegistrationController`          | `SITE_PRODUCT`              | Site Brand Tracking  |

### 1.4 Version 3 Endpoints (`/api/v3`)

|  #  | HTTP Method   | Endpoint Path               | Target Controller & Action                 | Flutter App Constant    | Domain / Feature       |
| :-: | :------------ | :-------------------------- | :----------------------------------------- | :---------------------- | :--------------------- |
| 36  | `POST`        | `/api/v3/login`             | `API\v3\MobileuserController@authenticate` | `LOGIN`                 | User Authentication    |
| 37  | `POST`        | `/api/v3/locations`         | `API\v3\LocationController@store`          | `POST_LOCATION`         | Real-time GPS Tracking |
| 38  | `POST`        | `/api/v3/order`             | `API\v3\OrderController@store`             | `ORDER`                 | Order Booking          |
| 39  | `GET`         | `/api/v3/orderHistory`      | `API\v3\OrderController@index`             | `ORDER_HISTORY`         | Order History          |
| 40  | `GET`         | `/api/v3/orderDetails/{id}` | `API\v3\OrderController@show`              | `ORDER_HISTORY_DETAILS` | Order Line Items       |
| 41  | `POST`, `GET` | `/api/v3/payment`           | `API\v3\PaymentController`                 | `COLLECTION`            | Payment Submission     |
| 42  | `GET`         | `/api/v3/userlist`          | `API\v3\MessageController@userlist`        | `USER_LIST`             | Chat User Directory    |
| 43  | `GET`, `POST` | `/api/v3/message`           | `API\v3\MessageController`                 | `MESSAGE`               | In-app Messaging       |

### 1.5 Version 4 Endpoints (`/api/v4`)

|  #  | HTTP Method | Endpoint Path              | Target Controller & Action                 | Flutter App Constant | Domain / Feature      |
| :-: | :---------- | :------------------------- | :----------------------------------------- | :------------------- | :-------------------- |
| 44  | `POST`      | `/api/v4/attendancewithkm` | `API\v4\AttendanceController@onlinewithkm` | `ATTENDANCE`         | GPS Attendance + KM   |
| 45  | `POST`      | `/api/v4/negotiation`      | `API\v4\SessionController@new`             | `UNLISTED_VISIT`     | Unlisted Dealer Visit |
| 46  | `*`         | `/api/v4/reminder`         | `API\v4\AlertReminderController`           | `REMINDER_ALERTS`    | Field Reminders       |
| 47  | `GET`       | `/api/v4/issue_type`       | `API\v4\ComplaintController@issue_type`    | `ISSUE_TYPE`         | Complaint Issue Types |
| 48  | `*`         | `/api/v4/complaint`        | `API\v4\ComplaintController`               | `COMPLAIN`           | Field Complaints      |

---

## 2. Inactive APIs (Not Used by Flutter App — To Be Commented Out)

These **41 endpoints** exist in the Laravel routing files but are **NOT** referenced by the Flutter mobile application. They will be safely commented out with explanatory headers.

### 2.1 Inactive in `routes/api.php`

|  #  | HTTP Method   | Endpoint Path                                                            | Current Controller & Action                   | Reason for Commenting Out                                            |
| :-: | :------------ | :----------------------------------------------------------------------- | :-------------------------------------------- | :------------------------------------------------------------------- |
|  1  | `POST`        | `/api/login`                                                             | `API\MobileuserController@authenticate`       | Superseded by `/api/v3/login`                                        |
|  2  | `GET`         | `/api/orderHistory`                                                      | `API\v3\OrderController@orderHistory`         | Superseded by `/api/v3/orderHistory`                                 |
|  3  | `GET`         | `/api/collectionHistory`                                                 | `API\PaymentController@collectionHistory`     | Superseded by `/api/v2/collectionHistory`                            |
|  4  | `GET`         | `/api/visitSchedule`                                                     | `API\MobileuserController@visitSchedule`      | Superseded by `/api/v2/visitSchedule`                                |
|  5  | `GET`         | `/api/today`                                                             | `API\MobileuserController@visitScheduleToday` | Not used in Flutter                                                  |
|  6  | `GET`         | `/api/visit/{date}`                                                      | `API\MobileuserController@visitHistory`       | Flutter uses `/api/visit` & `/api/rangehistory`                      |
|  7  | `GET`, `POST` | `/api/message`, `/api/send`                                              | `API\MessageController`                       | Superseded by `/api/v3/message`                                      |
|  8  | `GET`         | `/api/notice`, `/api/notice/{id}`                                        | `API\NoticeController`                        | Superseded by `/api/v2/notice`                                       |
|  9  | `GET`         | `/api/orderDetails/{id}`                                                 | `API\NoticeController@orderDetails`           | Superseded by `/api/v3/orderDetails`                                 |
| 10  | `GET`         | `/api/dealer/{code}`, `/api/dealer/{code}/update`                        | `API\DealerController`                        | Not used in Flutter                                                  |
| 11  | `POST`        | `/api/dealerlog`                                                         | `API\DeviceLogController@store`               | Not used in Flutter                                                  |
| 12  | `GET`         | `/api/{id}/history/{date}`                                               | `API\SessionController@index`                 | Not used in Flutter                                                  |
| 13  | `POST`        | `/api/negotiation`                                                       | `API\SessionController@new`                   | Superseded by `/api/v4/negotiation` & `/api/v5/negotiation`          |
| 14  | `POST`, `GET` | `/api/locations`, `/api/locationinfo`                                    | `API\SessionController`                       | Superseded by `/api/v3/locations`                                    |
| 15  | `GET`         | `/api/products`                                                          | `API\SessionController@index`                 | Superseded by `/api/v2/products`                                     |
| 16  | `POST`        | `/api/order`                                                             | `API\v3\OrderController@store`                | Superseded by `/api/v3/order`                                        |
| 17  | `POST`        | `/api/payment`                                                           | `API\PaymentController@store`                 | Superseded by `/api/v3/payment`                                      |
| 18  | `PATCH`       | `/api/updatestatus/{id}`                                                 | `API\PaymentController@updatestatus`          | Superseded by `/api/v2/updatestatus`                                 |
| 19  | `POST`        | `/api/attendance`, `/api/attendance/offline`                             | `API\AttendanceController`                    | Superseded by `/api/v4/attendancewithkm` & `/api/v2/attendance`      |
| 20  | `POST`        | `/api/device-log`                                                        | `API\DeviceLogController@store`               | Not used in Flutter                                                  |
| 21  | `Resource`    | `/api/site-registrations`                                                | `API\v2\SiteRegistrationController`           | Superseded by `/api/v2/site-registration` (commented out in Flutter) |
| 22  | `GET`         | `/api/unlisted-dealers`                                                  | `API\DealerController@unlistedDealers`        | Not used in Flutter                                                  |
| 23  | `POST`        | `/api/tada-expenses/images/delete`                                       | `API\TadaReportController@deleteExpenseImage` | Redundant with `DELETE /api/tada-expenses/images/{id}`               |
| 24  | `GET`         | `/api/due/{area_code}/{dealer_code}`                                     | `API\SessionController@getdue`                | Not used in Flutter                                                  |
| 25  | `GET`         | `/api/ageing/{area_code}/{dealer_code}`                                  | `API\SessionController@getdata`               | Not used in Flutter                                                  |
| 26  | `GET`, `POST` | `/api/test`, `/api/m`, `/api/all`, `/api/repeateschedule`, `/api/logout` | Various Controllers                           | Unused development / test endpoints                                  |

### 2.2 Inactive in `routes/v2/api.php`

|  #  | HTTP Method   | Endpoint Path                                            | Current Controller & Action                    | Reason for Commenting Out                            |
| :-: | :------------ | :------------------------------------------------------- | :--------------------------------------------- | :--------------------------------------------------- |
| 27  | `POST`        | `/api/v2/login`                                          | `API\v2\MobileuserController@authenticate`     | Flutter uses `/api/v3/login`                         |
| 28  | `GET`         | `/api/v2/countAmountandVisit`                            | `API\v2\PaymentController@countAmountandVisit` | Flutter uses `/api/countAmountandVisit` (v1)         |
| 29  | `GET`         | `/api/v2/today`, `/api/v2/visit`, `/api/v2/visit/{date}` | `API\v2\MobileuserController`                  | Flutter uses `/api/visit` & `/api/rangehistory` (v1) |
| 30  | `POST`, `GET` | `/api/v2/payment`, `/api/v2/payment/filter`              | `API\v2\PaymentController`                     | Flutter uses `/api/v3/payment`                       |
| 31  | `GET`         | `/api/v2/orderDetails/{id}`                              | `API\v2\NoticeController@orderDetails`         | Flutter uses `/api/v3/orderDetails`                  |
| 32  | `GET`         | `/api/v2/collectionDetails/{id}`                         | `API\v2\NoticeController@collectionDetails`    | Flutter uses `/api/collectionDetails` (v1)           |
| 33  | `GET`         | `/api/v2/all`                                            | `API\v2\NoticeController@all`                  | Not used in Flutter                                  |
| 34  | `POST`        | `/api/v2/attendance/offline`                             | `API\v2\AttendanceController@offline`          | Not used in Flutter                                  |
| 35  | `GET`         | `/api/v2/profile`                                        | `API\v2\MobileuserController@profile`          | Not used in Flutter                                  |

### 2.3 Inactive in `routes/v3/api.php`

|  #  | HTTP Method | Endpoint Path          | Current Controller & Action           | Reason for Commenting Out                                      |
| :-: | :---------- | :--------------------- | :------------------------------------ | :------------------------------------------------------------- |
| 36  | `GET`       | `/api/v3/order/filter` | `API\v3\OrderController@filter`       | Not used in Flutter                                            |
| 37  | `POST`      | `/api/v3/negotiation`  | `API\v3\SessionController@store`      | Flutter uses `/api/v4/negotiation` & `/api/v5/negotiation`     |
| 38  | `POST`      | `/api/v3/offline`      | `API\v3\AttendanceController@offline` | Not used in Flutter                                            |
| 39  | `Resource`  | `/api/v3/attendance`   | `API\v3\AttendanceController`         | Flutter uses `/api/v4/attendancewithkm` & `/api/v2/attendance` |

### 2.4 Inactive in `routes/v4/api.php`

|  #  | HTTP Method | Endpoint Path                   | Current Controller & Action                 | Reason for Commenting Out               |
| :-: | :---------- | :------------------------------ | :------------------------------------------ | :-------------------------------------- |
| 40  | `POST`      | `/api/v4/offline/negotiation`   | `API\v4\SessionController@store`            | Not used in Flutter                     |
| 41  | `POST`      | `/api/v4/location`              | `API\v4\SessionController@loctaion`         | Flutter uses `/api/v3/locations`        |
| 42  | `POST`      | `/api/v4/attendance` (standard) | `API\v4\AttendanceController@online`        | Flutter uses `/api/v4/attendancewithkm` |
| 43  | `POST`      | `/api/v4/offline`               | `API\v4\AttendanceController@offline`       | Not used in Flutter                     |
| 44  | `GET`       | `/api/v4/attendance`            | `API\v4\AttendanceController@getAttendance` | Flutter uses `/api/v2/attendance`       |

---

## 3. How These Active APIs Map to Root Modules (`modules/`)

During the Modular Monolith extraction into `/modules/`, only the active routes will be wired into the new module route files:

```text
modules/
├── Identity/
│   └── Routes/api.php        --> POST /api/v3/login
├── Territory/
│   └── Routes/api.php        --> GET  /api/dealers
│                             --> GET  /api/dealers/position
│                             --> GET  /api/dealers/reposition
│                             --> GET  /api/dealerWithboundry
├── Attendance/
│   └── Routes/api.php        --> GET  /api/v2/attendance
│                             --> POST /api/v2/attendance
│                             --> POST /api/v4/attendancewithkm
│                             --> POST /api/v3/locations
│                             --> GET  /api/visit
│                             --> GET  /api/rangehistory
├── Sales/
│   └── Routes/api.php        --> GET  /api/v2/products
│                             --> POST /api/v3/order
│                             --> GET  /api/v3/orderHistory
│                             --> GET  /api/v3/orderDetails/{id}
├── Finance/
│   └── Routes/api.php        --> GET  /api/countAmountandVisit
│                             --> GET  /api/collectionDetails/{id}
│                             --> GET  /api/v2/collectionHistory
│                             --> PATCH /api/v2/updatestatus/{id}
│                             --> POST /api/v3/payment
├── Expense/
│   └── Routes/api.php        --> GET, POST, PUT, DELETE /api/tada*
│                             --> POST /api/tada/upload-image
│                             --> DELETE /api/tada-expenses/images/{id}
│                             --> GET  /api/tada/authorized-users
│                             --> GET  /api/tada/expense-authorized-users
├── LeadPipeline/
│   └── Routes/api.php        --> GET, POST /api/contractors
│                             --> GET, POST, PATCH /api/v2/site-registration*
│                             --> GET, PATCH /api/v2/site-products*
├── Team/
│   └── Routes/api.php        --> GET  /api/my-teams
│                             --> GET  /api/my-team/{user}/{date}
└── Communication/
    └── Routes/api.php        --> GET  /api/v2/notice
                              --> GET  /api/v2/notice/{id}
                              --> GET  /api/v3/userlist
                              --> GET, POST /api/v3/message
                              --> POST /api/v4/negotiation (unlisted visit)
                              --> POST /api/v5/negotiation (unlisted visit v5)
                              --> Resource /api/v4/reminder
                              --> GET  /api/v4/issue_type
                              --> Resource /api/v4/complaint
```
