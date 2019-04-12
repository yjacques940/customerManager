using Microsoft.AspNetCore.Mvc;
using System;
using System.Collections.Generic;
using WebApi.DTO;
using WebApi.Models;
using WebApi.Services;

namespace WebApi.Controllers
{
    [Route("api/[controller]")]
    [ApiController]
    public class TimeSlotsController : BaseController<TimeSlotService, TimeSlot>
    {
        public TimeSlotsController(TimeSlotService service) : base(service)
        {
        }

        [HttpPost, Route("Add")]
        public ActionResult AddNewTimeSlot([FromBody]TimeSlot timeSlot)
        {
            if (Service.IsAvailable(timeSlot))
            {
                if (Service.CreateNewTimeSlot(timeSlot))
                {
                    return Ok();
                }
                return BadRequest();
            }
            return Conflict();
        }

        [HttpGet, Route("GetFreeTimeSlots")]
        public ActionResult GetFreeTimeSlots()
        {
            List<TimeSlot> dates = Service.GetFreeTimeSlots();
            if (dates != null)
                return Ok(dates);

            return BadRequest();
        }

        [HttpPost, Route("GetTimeSlotForADay")]
        public ActionResult GetTimeSlotForTheDay([FromBody] Date date)
        {
            List<TimeSlot> timeSlots = Service.GetTimeSlotForTheDay(date.AppointmentDate);
            if (timeSlots != null)
            {
                ConvertToString(timeSlots);
                return Ok(timeSlots);
            }
            return BadRequest();
        }

        private void ConvertToString(List<TimeSlot> timeSlots)
        {
            foreach (var timeSlot in timeSlots)
            {
                timeSlot.StartDateTime = Convert.ToDateTime(timeSlot.StartDateTime.ToString("Y-m-d H:m:i"));
            }
        }

        [HttpGet, Route("CheckTimeSlotAvailable/{id}")]
        public ActionResult CheckTimeSlotAvailable(int id)
        {
            if (Service.CheckTimeSlotAvailable(id))
                return Ok();

            return BadRequest();
        }
    }
}
