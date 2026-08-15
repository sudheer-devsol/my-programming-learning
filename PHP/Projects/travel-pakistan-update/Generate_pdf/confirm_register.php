<?php

    session_start();

    require("PDF/fpdf.php");

    if(!isset($_SESSION["register_pdf"])){
        header("Location: ../register.php");
        exit;
    }

    $data = $_SESSION["register_pdf"];

    // =========Creating object==============

    $pdf = new FPDF();
    $pdf->AddPage();

    $pdf->SetFont("Arial","B",18);
    $pdf->Cell(0,10,"Travel Pakistan",0,1,"C");
    $pdf->SetFont("Arial","",13);

    $pdf->Cell(0,10,"Registration Confirmation",0,1);

    $pdf->Ln(5);

    $pdf->Cell(60,10,"First Name:");
    $pdf->Cell(100,10,$data["first_name"],0,1);

    $pdf->Cell(60,10,"Last Name:");
    $pdf->Cell(100,10,$data["last_name"],0,1);

    $pdf->Cell(60,10,"Email:");
    $pdf->Cell(100,10,$data["email"],0,1);

    $pdf->Cell(60,10,"Password:");
    $pdf->Cell(100,10,$data["password"],0,1);

    $pdf->Cell(60,10,"Gender:");
    $pdf->Cell(100,10,$data["gender"],0,1);

    $pdf->Cell(60,10,"Date Of Birth:");
    $pdf->Cell(100,10,$data["date_of_birth"],0,1);

    $pdf->Cell(60,10,"Address:");
    $pdf->MultiCell(120,10,$data["address"]);

    $pdf->Ln(5);

    $pdf->SetFont("Arial","B",14);

    $pdf->Cell(0,10,"Account Status",0,1);

    $pdf->SetFont("Arial","",12);

    $pdf->MultiCell(180, 8,

    "Your registration has been completed successfully.

    Current Approval Status : Pending

    Current Account Status : Inactive

    Your account is awaiting Admin approval.

    Once the Admin approves and activates your account, you will receive an email notification.

    After approval, you can login to Travel Pakistan."
    );

    $pdf->Output("D","Travel_Pakistan_Registration.pdf");


?>