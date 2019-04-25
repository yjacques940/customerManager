using Microsoft.AspNetCore.Mvc;
using Microsoft.Extensions.Configuration;
using System;
using System.Collections.Generic;
using WebApi.Data;
using WebApi.DTO;
using WebApi.Models;
using WebApi.Services;
using WebApi.Validators;

namespace WebApi.Controllers
{
    [Route("api/[controller]")]
    [ApiController]
    public class AppointmentsController : BaseReaderController<AppointmentService, Appointment>
    {
        private readonly PhoneNumberService phoneNumberService;
        private readonly IConfiguration configuration;

        public AppointmentsController(WebApiContext context, AppointmentService service,
            PhoneNumberService phoneNumberService, IConfiguration configuration) : base(service)
        {
                this.phoneNumberService = phoneNumberService;
                this.configuration = configuration;
        }

        [HttpGet, Route("GetByDate/{date}")]
        public ActionResult<IEnumerable<AppointmentTimeSlotInformation>> GetAppointmentsByDate(string date)
        {
            return Service.GetAppointmentsByDate(date);
        }

        [HttpGet, Route("AppointmentsAndCustomers")]
        public ActionResult<IEnumerable<CustomerAppointmentInformation>> GetAppointmentAndCustomers()
        {
            return Service.GetAppointmentAndCustomers(phoneNumberService);
        }

        [HttpGet, Route("NewAppointments")]
        public ActionResult<IEnumerable<CustomerAppointmentInformation>> GetNewAppointments()
        {
            return Service.GetNewAppointments(phoneNumberService);
        }

        [HttpPost, Route("ChangeIsNewStatus")]
        public ActionResult ChangeIsNewStatus([FromBody]List<int> ids)
        {
            if (Service.ChangeIsNewStatus(ids))
                return Ok();
            return Conflict();
        }

        [HttpPost, Route("CheckAppointmentIsAvailable/{appointment}")]
        [ProducesResponseType(401)]
        [ProducesResponseType(200)]
        public ActionResult AddAppointment([FromBody]AppointmentInformation appointment)
        {
            if (appointment == null)
                return BadRequest();
            var newAppointment = Service.CheckAppointmentIsAvailable(appointment);
            if (newAppointment == null)
                return Conflict();
            newAppointment.IsNew = true;
            newAppointment.IsActive = true;
            var appointmentAdded = Service.AddOrUpdate(newAppointment);
            var user = Service.GetUser(appointmentAdded);
            var appointmentDate = Service.GetAppointmentTimeSlot(newAppointment).StartDateTime;
            if (user != null)
                EmailSender.SendConfirmationEmail(user.Email, appointmentDate, configuration);
            return Ok(appointmentAdded);
        }

        [HttpPost, Route("AskForAppointment")]
        [ProducesResponseType(401)]
        [ProducesResponseType(200)]
        public ActionResult AskForAppointment([FromBody]AskForAppointmentInformation requestInfo)
        {
            return Ok(Service.SendAppointmentRequest(requestInfo,configuration));
        }

        [HttpDelete]
        [Route("{id:int}")]
        [ProducesResponseType(401)]
        [ProducesResponseType(200)]
        public virtual ActionResult Delete(int id)
        {
            if (Service.Remove(id))
            {
                return NoContent();
            }
            return BadRequest();
        }

        [HttpPost, Route("ReserveAnAppointment")]
        public ActionResult ReserveAnAppointment([FromBody]AppointmentUserInformation appointmentService)
        {
            if(appointmentService.IdUser != 0)
            {
                if (Service.ReserveAnAppointment(appointmentService,configuration))
                {
                    return Ok();
                }
                return Conflict();
            }
            return BadRequest();
        }
    }
}
