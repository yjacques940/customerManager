using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using Microsoft.AspNetCore.Mvc;
using WebApi.Data;
using WebApi.DTO;
using WebApi.Models;

namespace WebApi.Services
{
    public class QuestionService : BaseCrudService<Question>
    {
        public QuestionService(IWebApiContext context) : base(context)
        {
        }
    }
}
