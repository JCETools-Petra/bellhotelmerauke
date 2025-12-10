@echo off
echo ========================================
echo Testing Hoteliermarket API Directly
echo ========================================
echo.

set API_KEY=htk_NfMhypcZDiZrXtyyUL24vsi1eTcPDFVNVmSvprEvDqdbLy0F
set PROPERTY_ID=14
set API_URL=https://hoteliermarket.my.id/api/properties/%PROPERTY_ID%/room-pricing

echo Testing with:
echo API Key: %API_KEY%
echo Property ID: %PROPERTY_ID%
echo URL: %API_URL%
echo.
echo ----------------------------------------
echo Making API Request...
echo ----------------------------------------
echo.

curl -v -H "X-API-Key: %API_KEY%" -H "Accept: application/json" %API_URL%

echo.
echo ========================================
echo Test Complete
echo ========================================
pause
