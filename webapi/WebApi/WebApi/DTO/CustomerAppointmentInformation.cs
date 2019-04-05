﻿using System.Collections.Generic;
using WebApi.Models;

namespace WebApi.DTO
{
    public class CustomerAppointmentInformation
    {
        public Appointment Appointment { get; set; }
        public Customer Customer { get; set; }
        public List<PhoneNumber> PhoneNumbers { get; set; }
    }
}