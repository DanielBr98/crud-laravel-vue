<?php

use Illuminate\Http\Request;

$router->post('/contact/list', function () {
    return response()->json(DB::select("SELECT * FROM contacts ORDER BY name ASC"));
});

$router->post('/contact/delete', function (Request $request) {
    DB::delete("DELETE FROM contacts WHERE id = ?", [$request->input("id")]);
});

$router->post('/contact/save', function (Request $request) {
    if ($request->input("id") != "") {
        DB::update("UPDATE contacts SET name = ?, email = ?, phone = ?, updated_at = NOW() WHERE id = ?", [
            $request->input("name"),
            $request->input("email"),
            $request->input("phone"),
            $request->input("id")
        ]);
    } else {
        DB::insert("INSERT INTO contacts (name, email, phone, created_at) VALUES (?, ?, ?, NOW())", [
            $request->input("name"),
            $request->input("email"),
            $request->input("phone")
        ]);
    }
});
