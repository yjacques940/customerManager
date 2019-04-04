<?php
$titre = 'AppointmentCreation';
ob_start(); ?>
    <section class="contact py-lg-4 py-md-3 py-sm-3 py-3">
        <div class="container py-lg-5 py-md-4 py-sm-4 py-3">
            <h3 class="title text-center mb-md-4 mb-sm-3 mb-3 mb-2"><?php echo localize("PageTitle-NewAppointment") ?></h3>
            <form class="row w3pvt-info-para pt-lg-5 pt-md-4 pt-3" id="frm_newAppointment" method="post">
                <div class="col-lg-6 col-md-6">
                    <h4><?php echo localize('CreateAppointment-DetailsAppointment-Text'); ?></h4>
                    <div class="w3pvt-wls-contact-mid">
                        <div class="form-group contact-forms">
                            <label for="appointmentDate"><p><?php echo localize('Appointment-Date'); ?></p></label>
                            <input type="date" min="<?php echo date('Y-m-d'); ?>" id="appointmentDate" name="appointmentDate" class="form-control" placeholder="Date du rendez-vous" required>
                        </div>
                        <div class="form-group contact-forms">
                            <label for="appointmentTime"><p><?php echo localize('Appointment-Time'); ?></p></label>
                            <input type="time" name="appointmentTime" id="appointmentTime" class="form-control" placeholder="Heure du rendez-vous" required>
                        </div>
                        <div class="form-group contact-forms">
                            <label for="appointmentDuration"><?php echo localize('Appointment-Duration'); ?></label>
                            <select id="appointmentDuration" name="appointmentDuration">
                                <option disabled selected value> --:-- </option>
                                <option value="1:00"><?php echo localize('CreateAppointment-OneHour'); ?></option>
                                <option value="1:30"><?php echo localize('CreateAppointment-OneHourAndHalf'); ?></option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 ">
                    <div >
                        <h4><?php echo localize('CreateAppointment-DetailsCustomer-Text'); ?></h4>
                    </div>
                    <div class=" mt-3">
                        <table class="table table-sm table-hover" id="tbl_customers">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col"><?php echo localize('Appointment-Date'); ?></th>
                                    <th scope="col"><?php echo localize('Appointment-Customer'); ?></th>
                                    <th scope="col"><?php echo localize('Personal-Phone'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $customers = CallAPI('GET', 'Customers');
                                $count = 0;
                                foreach ($customers as $customer) {
                                    ?>
                                <tr class="clickable-row" id="<?php echo $customer->id; ?>">
                                    <td scope="row">
                                    <?php
                                        echo $customer->firstName; ?>
                                    </td>
                                    <td>
                                    <?php
                                        echo $customer->lastName; ?>
                                    </td>
                                    <td>
                                    <?php
                                    $phoneNumbers = CallAPI('GET', 'CustomerPhoneNumbers/ForCustomer/'.($customer->id));
                                    foreach ($phoneNumbers as $phoneNumber) {
                                        ?>
                                        <table style="width:100%; background-color: rgba(255,255,255,0)">
                                            <tr>
                                                <th><?php echo $phoneNumber->idPhoneType; ?></th>
                                                <td><?php echo $phoneNumber->phone.$phone->extension; ?></td>
                                            </tr>
                                        </table>
                                    <?php
                                    } ?>
                                    </td>
                                </tr>
                                <?php
                                    $count++;
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <button type="submit" class="btn sent-butnn" value="<?php echo localize('CreateAppointment-MakeAppointment'); ?>" id="btn_makeAppointment"><?php echo localize('CreateAppointment-MakeAppointment'); ?></button>
                <div id="submitResult"></div>
            </form>
        </div>
    </section>

<?php
require('OnClick.html');
$contenu = ob_get_clean();
$onHomePage = false;
require 'gabarit.php'; ?>
