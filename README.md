# 360 Monitoring for Managed Services Provisioning Module for WHMCS Version 1.0 #

## Installation ##

- Extract the contents of the release zip file recursively in your WHMCS root folder. The module will be extracted to /modules/servers/managed360monitoring and other folders won't be touched
- The module is now installed and can be used to configure new products
- Don't forget to remove the zip file afterwards

## Prerequisites ##

- You need a valid 360 Monitoring Subscription with sufficient numbers of unused servers and websites to create monitors afterwards
- For the WHMCS module to access the 360 Monitoring API you need to create an API key first:
  - Log in to 360 Monitoring with a valid account
  - Visit [https://monitoring.platform360.io/api-keys/list](https://monitoring.platform360.io/api-keys/list) and create an API key there for this WHMCS module with read and write permissions
  - Write down the API key as it can only be viewed one time

## Example Configuration as Addon ##

- Login to your WHMCS instance as an admin user
- Go to Configuration -> Sytem-Settings -> Products Addons
  - Create a new product addon e.g. "Monitoring for your site - Powered by 360 Monitoring" with a nice description like "Keep track of your website uptime with our easy to use monitoring addon"
  - Optionally you can add a nice product image that has been delivered within the module's zip archive e.g.  
    `Keep track of your website uptime with our easy to use monitoring addon <br/>`
    `<img height="50" src="./modules/servers/managed360monitoring/360monitoring.png">`
  - Check "Show addon during initial product order process" if you want the addon to be ordered e.g. with a virtual server in the initial order process
  - Select the appropriate "Welcome email" if you want your customers to receive a separate email for the addon after ordering. This is normally the "Other Product/Service Welcome Email"
  - Switch to the tab "Pricing" and select the payment type, e.g. "Recurring" and enable the "One Time/Monthly" option for selected currencies with the price you like to have as the monthly fee.
  - Switch to the tab "Module Settings" and choose "Other" for the product type together with "360 Monitoring for Managed Services" as the module name
  - Fill the field "API Key" with the newly created API key from above
  - For "Monitoring Type" select the appropriate type of monitoring you want to have in this addon. Default is "Website Monitor".
  - Check "Automatically setup the product as soon as an order is placed" if you want a good user experience or any other option that matches your existing process 
  - Switch to the tab "Applicable Products" and select all the products where this addon will be available for 
  - IMPORTANT: You can combine monitoring with every product you like as long as the "domain" field will be set accordingly by the parent product 
  - Click on "Save Changes"

## Troubleshooting ##

In case of problems pleae have a look at the "Module Log" by visiting Configuration -> System Logs and selecting "Module Log" on the left sidebar.

## Minimum Requirements ##

The 360 Monitoring Provisioning Module has been tested with WHMCS versions 7.8 and higher.

For the latest WHMCS minimum system requirements, please refer to
https://docs.whmcs.com/System_Requirements

## Copyright ##

Copyright 2022 [Plesk International GmbH](https://www.plesk.com)