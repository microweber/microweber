using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;
using System.Web.Services;

public partial class example : System.Web.UI.Page
{
    protected void Page_Load(object sender, EventArgs e)
    {
        if (!IsPostBack)
        {
            Gallery.DataSource = new string[] { "blue", "orange", "brown", "red", "yellow", "green", "black", "white", "purple" };
            Gallery.DataBind();
        }
    }

    [WebMethod]
    public static void SaveListOrder(int[] ids)
    {
        for (int i = 0; i < ids.Length; i++)
        {
            int id = ids[i];
            int ordinal = i;
            //...
        }
    }
}
