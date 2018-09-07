<?php
//Normal User Routes (logged in and logged out)
$app->post("/v1/sendResume", "ResumeSender:send");
$app->get("/", "Home:index");