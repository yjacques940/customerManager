﻿using Microsoft.AspNetCore.Mvc;
using System;
using System.Collections.Generic;
using WebApi.DTO;
using WebApi.Models;
using WebApi.Services;

namespace WebApi.Controllers
{
    [Route("api/[controller]")]
    [ApiController]
    public class TimeSlotController : BaseController<TimeSlotService, TimeSlot>
    {
        public TimeSlotController(TimeSlotService service) : base(service)
        {
        }

        [HttpPost, Route("GetTimeSlotForADay")]
        public ActionResult GetTimeSlotForTheDay([FromBody] Date date)
        {
            List<TimeSlot> timeSlots = Service.GetTimeSlotForTheDay(date.AppointmentDate);
            if (timeSlots != null)
                return Ok(timeSlots);

            return BadRequest();
        }

        [HttpGet, Route("GetFreeTimeSlots")]
        public ActionResult GetFreeTimeSlots()
        {
            List<TimeSlot> dates = Service.GetFreeTimeSlots();
            if (dates != null)
                return Ok(dates);

            return BadRequest();
        }
    }
}
